<?php
namespace App\Http\Controllers\Avukat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Katip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AvukatChatController extends Controller
{
    public function index()
    {
        $user = auth('avukat')->user();

        // Tüm katipleri al
        $allKatipler = Katip::select('id', 'username', 'is_active', 'last_active_at')->get();

        // Mevcut sohbetleri al
        $conversations = Conversation::where('avukat_id', $user->id)
            ->with([
                'katip' => function ($q) {
                    $q->select('id', 'username', 'is_active', 'last_active_at');
                },
                'katip.avatar',
                'messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest()
            ->get()
            ->keyBy('katip_id'); // Katip ID'sine göre indeksleme

        // Tüm katipler için sohbet bilgisini ekle
        $katiplerWithConversations = $allKatipler->map(function ($katip) use ($conversations) {
            $katip->conversation = $conversations->get($katip->id);
            return $katip;
        });

        return view('avukat.mesajlasma.panel', compact('katiplerWithConversations'));
    }


    public function startConversation(Request $request)
    {
        $request->validate(['katip_id' => 'required|exists:katips,id']);

        $avukat = auth('avukat')->user();
        $katipId = $request->katip_id;

        $existing = Conversation::where('avukat_id', $avukat->id)->where('katip_id', $katipId)->first();
        if ($existing) {
            return redirect()->route('avukat.chat.show', $existing->id);
        }

        $conversation = Conversation::create([
            'avukat_id' => $avukat->id,
            'katip_id'  => $katipId,
        ]);

        return redirect()->route('avukat.chat.show', $conversation->id);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'contenti' => 'nullable|string',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,png,jpeg',
        ]);

        // Ek kontrol: Metin veya dosya olmalı
        if (empty($request->contenti) && !$request->hasFile('file')) {
            return response()->json(['error' => 'Mesaj veya resim gereklidir'], 422);
        }

        $user = auth('avukat')->user();

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' => 'Avukat',
            'sender_id' => $user->id,
            'receiver_type' => 'Katip',
            'receiver_id' => $this->getReceiverId($request->conversation_id),
            'message' => $request->contenti ?? null,
        ]);

        // Dosya yükleme
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if (!$file->isValid()) {
                Log::error('Uploaded file is not valid: ' . $file->getErrorMessage());
                return response()->json(['error' => 'Uploaded file is not valid'], 400);
            }

            try {
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileExtension = $file->getClientOriginalExtension();

                $directory = public_path('uploads/mesajlar');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                    Log::info('Created directory: ' . $directory);
                }

                $fileName = 'message_' . time() . '.' . $fileExtension;
                $file->move($directory, $fileName);

                $path = 'uploads/mesajlar/' . $fileName;
                Log::info('File moved successfully to: ' . $path);

                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path'  => $path,
                    'file_name'  => $originalName,
                    'file_size'  => $fileSize,
                    'file_type'  => $fileExtension,
                ]);

                // Broadcast öncesi attachments'ı yükle
                $message->load('attachments');
            } catch (\Exception $e) {
                Log::error('Failed to move file: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to move file: ' . $e->getMessage()], 500);
            }
        }

        broadcast(new \App\Events\MessageSent($message));
        return response()->json(['message' => $message], 200);
    }

    private function getReceiverId($conversationId)
    {
        return Conversation::find($conversationId)->katip_id;
    }

    public function setOffline(Request $request)
    {
        $user = auth('avukat')->user();
        $user->update([
            'is_active' => false,
            'last_active_at' => now(),
        ]);

        event(new \App\Events\UserStatusUpdated($user->id, 'Avukat', false, now()));

        return response()->json(['status' => 'offline']);
    }

    public function newchat(Request $request)
    {
        $request->validate([
            'katip_id' => 'required|exists:katips,id',
        ]);

        $avukat = auth('avukat')->user();
        $katipId = $request->katip_id;

        // Check if a conversation already exists
        $existing = Conversation::where('avukat_id', $avukat->id)
            ->where('katip_id', $katipId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'conversation_id' => $existing->id,
            ]);
        }

        // Create a new conversation
        try {
            $conversation = Conversation::create([
                'avukat_id' => $avukat->id,
                'katip_id' => $katipId,
            ]);

            // Update the sidebar by fetching the new conversation data
            $katip = Katip::select('id', 'username', 'is_active', 'last_active_at')
                ->with('avatar')
                ->find($katipId);

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'katip' => [
                    'id' => $katip->id,
                    'username' => $katip->username,
                    'is_active' => $katip->is_active,
                    'last_active_at' => $katip->last_active_at ? $katip->last_active_at->toDateTimeString() : null,
                    'avatar' => $katip->avatar ? asset($katip->avatar->path) : asset('upload/no_image.jpg'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create new conversation: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Yeni sohbet başlatılamadı'], 500);
        }
    }

    public function show($id)
    {
        $conversation = Conversation::with([
            'messages' => fn($q) => $q->orderBy('created_at', 'asc')->with('attachments'),
            'katip.avatar',
            'avukat.avatar',
        ])->findOrFail($id);

        $katip = $conversation->katip;
        $avukat = $conversation->avukat;

        $katipAvatarUrl = $katip->avatar
            ? asset($katip->avatar->path)
            : asset('upload/no_image.jpg');

        $avukatAvatarUrl = $avukat->avatar
            ? asset($avukat->avatar->path)
            : asset('upload/no_image.jpg');

        return response()->json([
            'conversation_id' => $conversation->id,
            'katip_name'      => $katip->username,
            'katip_avatar'    => $katipAvatarUrl,
            'katip_is_active' => $katip->is_active,
            'katip_last_active_at' => $katip->last_active_at ? $katip->last_active_at->toDateTimeString() : null,
            'avukat_avatar'   => $avukatAvatarUrl,
            'avukat_is_active' => $avukat->is_active,
            'messages'        => $conversation->messages->map(function ($message) use ($katipAvatarUrl, $avukatAvatarUrl) {
                return [
                    'id'            => $message->id,
                    'sender_type'   => $message->sender_type,
                    'sender_id'     => $message->sender_id,
                    'message'       => $message->message,
                    'created_at'    => $message->created_at->format('H:i'),
                    'sender_avatar' => $message->sender_type === 'Avukat' ? $avukatAvatarUrl : $katipAvatarUrl,
                    'attachments'   => $message->attachments->map(function ($attachment) {
                        return [
                            'file_name' => $attachment->file_name,
                            'file_size' => number_format($attachment->file_size / 1024, 2), // KB cinsine çevir
                            'file_type' => $attachment->file_type,
                            'url'       => asset($attachment->file_path),
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ]);
    }
}