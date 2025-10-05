<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Tüm konuşmaları avukat ve katip bilgileriyle birlikte getir
        $conversations = Conversation::with([
            'avukat:id,name,username',
            'katip:id,name,username',
            'messages' => function($query) {
                $query->latest()->limit(1); // Son mesajı al
            }
        ])
        ->whereHas('messages') // Sadece mesajı olan konuşmaları getir
        ->latest()
        ->get();

        return view('admin.mesajlar.index', compact('conversations'));
    }

    public function show($conversationId)
    {
        // Seçili konuşmanın tüm mesajlarını getir
        $conversation = Conversation::with([
            'avukat:id,name,username',
            'katip:id,name,username',
            'messages' => function($query) {
                $query->with('attachments')->orderBy('created_at', 'asc');
            }
        ])->findOrFail($conversationId);


        // JSON response olarak döndür
        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content ?? $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_id' => $message->sender_id,
                    'receiver_type' => $message->receiver_type,
                    'receiver_id' => $message->receiver_id,
                    'created_at' => $message->created_at->format('d.m.Y H:i'),
                    'created_at_human' => $message->created_at->diffForHumans(),
                    'attachments' => $message->attachments->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'file_name' => $attachment->file_name,
                            'file_path' => $attachment->file_path,
                            'file_size' => $attachment->file_size,
                            'file_type' => $attachment->file_type,
                            'download_url' => asset($attachment->file_path)
                        ];
                    })
                ];
            }),
            'avukat' => $conversation->avukat,
            'katip' => $conversation->katip
        ]);
    }
}
