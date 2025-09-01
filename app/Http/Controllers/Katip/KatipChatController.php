<?php
namespace App\Http\Controllers\Katip;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Avukat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KatipChatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('katip')->user();

        // Tüm avukatları al
        $allAvukatlar = Avukat::select('id', 'username', 'is_active', 'last_active_at')
            ->with('avatar')
            ->get();

        // Mevcut sohbetleri al
        $conversations = Conversation::where('katip_id', $user->id)
            ->with([
                'avukat' => function ($q) {
                    $q->select('id', 'username', 'is_active', 'last_active_at');
                },
                'avukat.avatar',
                'messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->latest()
            ->get()
            ->keyBy('avukat_id');

        // Tüm avukatlar için sohbet bilgisini ekle
        $avukatlarWithConversations = $allAvukatlar->map(function ($avukat) use ($conversations) {
            $avukat->conversation = $conversations->get($avukat->id);
            return $avukat;
        });

        // Hangi sohbetin gösterileceğini belirle
        $targetConversation = null;
        
        if ($request->has('conversation_id')) {
            // Belirli bir sohbet istendi
            $targetConversation = Conversation::where('katip_id', $user->id)
                ->find($request->conversation_id);
        } elseif ($request->has('avukat_id')) {
            // Belirli bir avukatla sohbet istendi
            $avukatId = $request->avukat_id;
            $targetConversation = Conversation::where('katip_id', $user->id)
                ->where('avukat_id', $avukatId)
                ->first();
            
            // Eğer sohbet yoksa oluştur
            if (!$targetConversation) {
                $targetConversation = Conversation::create([
                    'katip_id' => $user->id,
                    'avukat_id' => $avukatId,
                ]);
            }
        } else {
            // İlk aktif sohbeti bul
            foreach ($avukatlarWithConversations as $avukat) {
                if ($avukat->conversation) {
                    $targetConversation = $avukat->conversation;
                    break;
                }
            }

            // Eğer hiç sohbet yoksa, ilk avukatla sohbet oluştur
            if (!$targetConversation && $avukatlarWithConversations->count() > 0) {
                $firstAvukat = $avukatlarWithConversations->first();
                $targetConversation = Conversation::create([
                    'katip_id' => $user->id,
                    'avukat_id' => $firstAvukat->id,
                ]);
                $firstAvukat->conversation = $targetConversation;
            }
        }

        // Sohbet detaylarını al
        $currentConversation = null;
        $currentMessages = collect();
        $currentAvukat = null;

        if ($targetConversation) {
            $currentConversation = Conversation::with([
                'messages' => fn($q) => $q->orderBy('created_at', 'asc')->with('attachments'),
                'katip.avatar',
                'avukat.avatar',
            ])->find($targetConversation->id);

            $currentAvukat = $currentConversation->avukat;
            $currentMessages = $currentConversation->messages;
        }

        return view('katip.mesajlasma.panel', compact(
            'avukatlarWithConversations', 
            'currentConversation', 
            'currentMessages', 
            'currentAvukat'
        ));
    }

    public function show($id)
    {
        // Belirli bir sohbeti göstermek için index metodunu çağır
        $request = request()->merge(['conversation_id' => $id]);
        return $this->index($request);
    }

    public function startConversation(Request $request)
    {
        $request->validate(['avukat_id' => 'required|exists:avukats,id']);

        $katip = auth('katip')->user();
        $avukatId = $request->avukat_id;

        $existing = Conversation::where('katip_id', $katip->id)->where('avukat_id', $avukatId)->first();
        if ($existing) {
            return redirect()->route('katip.chat.show', $existing->id);
        }

        $conversation = Conversation::create([
            'katip_id'  => $katip->id,
            'avukat_id' => $avukatId,
        ]);

        return redirect()->route('katip.chat.show', $conversation->id);
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

        $user = auth('katip')->user();

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' => 'Katip',
            'sender_id' => $user->id,
            'receiver_type' => 'Avukat',
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
        return Conversation::find($conversationId)->avukat_id;
    }

    public function setOffline(Request $request)
    {
        $user = auth('katip')->user();
        $user->update([
            'is_active' => false,
            'last_active_at' => now(),
        ]);

        event(new \App\Events\UserStatusUpdated($user->id, 'Katip', false, now()));

        return response()->json(['status' => 'offline']);
    }

    public function newchat(Request $request)
    {
        $request->validate([
            'avukat_id' => 'required|exists:avukats,id',
        ]);

        $katip = auth('katip')->user();
        $avukatId = $request->avukat_id;

        // Check if a conversation already exists
        $existing = Conversation::where('katip_id', $katip->id)
            ->where('avukat_id', $avukatId)
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
                'katip_id' => $katip->id,
                'avukat_id' => $avukatId,
            ]);

            // Update the sidebar by fetching the new conversation data
            $avukat = Avukat::select('id', 'username', 'is_active', 'last_active_at')
                ->with('avatar')
                ->find($avukatId);

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'avukat' => [
                    'id' => $avukat->id,
                    'username' => $avukat->username,
                    'is_active' => $avukat->is_active,
                    'last_active_at' => $avukat->last_active_at ? $avukat->last_active_at->toDateTimeString() : null,
                    'avatar' => $avukat->avatar ? asset($avukat->avatar->path) : asset('upload/no_image.jpg'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create new conversation: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Yeni sohbet başlatılamadı'], 500);
        }
    }
}