<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Avukat;
use App\Models\Katip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::where(function ($query) use ($user) {
            if ($user instanceof \App\Models\Avukat) {
                $query->where('avukat_id', $user->id);
            } elseif ($user instanceof \App\Models\Katip) {
                $query->where('katip_id', $user->id);
            }
        })->with(['messages' => function($q) {
            $q->latest()->limit(1); // sadece son mesaj
        }, 'katip'])->latest()->get();

        return view('avukat.mesajlasma.index', compact('conversations'));
    }


    public function startConversation(Request $request)
    {
        $request->validate([
            'katip_id' => 'required|exists:katips,id'
        ]);

        $avukat = auth()->user();
        $katipId = $request->katip_id;

        // Eğer konuşma varsa, o konuşmaya yönlendir
        $existing = Conversation::where('avukat_id', $avukat->id)
            ->where('katip_id', $katipId)
            ->first();

        if ($existing) {
            return redirect()->route('chat.show', $existing->id);
        }

        // Yoksa yeni konuşma oluştur
        $conversation = Conversation::create([
            'avukat_id' => $avukat->id,
            'katip_id' => $katipId,
        ]);

        return redirect()->route('chat.show', $conversation->id);
    }

    public function show($id)
    {
        $conversation = Conversation::with(['messages', 'katip'])->findOrFail($id);
        return response()->json([
            'conversation_id' => $conversation->id,
            'username' => $conversation->katip->username,
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('H:i'),
                ];
            }),
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'contenti' => 'required|string'
        ]);

        $user = auth()->user();

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' => 'Avukat', // 👈 BURADA SABİTLEDİK
            'sender_id' => $user->id,
            'receiver_type' => 'Katip',
            'receiver_id' => $this->getReceiverId($request->conversation_id, $user),
            'message' => $request->contenti,
        ]);

        broadcast(new \App\Events\MessageSent($message));

        return response()->json(['message' => $message], 200);
    }

    private function getReceiverId($conversationId, $user)
    {
        $conversation = Conversation::find($conversationId);

        if ($user instanceof \App\Models\Avukat) {
            return $conversation->katip_id;
        } elseif ($user instanceof \App\Models\Katip) {
            return $conversation->avukat_id;
        }

        return null;
    }
}
