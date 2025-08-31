<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('conversation-' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'message-sent';
    }

    public function broadcastWith()
    {
        // İlgili modelleri ve ilişkileri yükle
        $this->message->load([
            'conversation.avukat.avatar',
            'conversation.katip.avatar',
            'attachments' // Dosya eklerini yükle
        ]);

        $conv = $this->message->conversation;

        // Kim gönderici?
        $senderType = $this->message->sender_type;
        if ($senderType === 'Avukat') {
            $sender = $conv->avukat;
        } else {
            $sender = $conv->katip;
        }

        // Avatar path’i (yoksa null)
        $avatarPath = optional($sender->avatar)->path;
        $avatarUrl = $avatarPath
            ? asset($avatarPath)
            : asset('upload/no_image.jpg');

        // Gönderilecek payload
        return [
            'conversation_id' => $conv->id,
            'sender_type'     => $senderType,
            'sender_name'     => $sender->username, // veya ->name, modele göre
            'sender_avatar'   => $avatarUrl,
            'message'         => $this->message->message,
            'created_at'      => $this->message->created_at->format('H:i'),
            'attachments'     => $this->message->attachments->map(function ($attachment) {
                return [
                    'file_name' => $attachment->file_name,
                    'file_size' => number_format($attachment->file_size / 1024, 2), // KB cinsine çevir
                    'file_type' => $attachment->file_type,
                    'url'       => asset($attachment->file_path),
                ];
            })->toArray(),
        ];
    }
}
