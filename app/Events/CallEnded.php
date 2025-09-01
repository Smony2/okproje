<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CallEnded implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public int $conversationId;
    public string $room;
    public string $fromType;
    public int $fromId;
    public string $toType;
    public int $toId;

    public function __construct(int $conversationId, string $room, string $fromType, int $fromId, string $toType, int $toId)
    {
        $this->conversationId = $conversationId;
        $this->room = $room;
        $this->fromType = $fromType;
        $this->fromId = $fromId;
        $this->toType = $toType;
        $this->toId = $toId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('conversation-' . $this->conversationId),
            new Channel('user-' . $this->toType . '-' . $this->toId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call-ended';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'room' => $this->room,
            'from_type' => $this->fromType,
            'from_id' => $this->fromId,
            'to_type' => $this->toType,
            'to_id' => $this->toId,
        ];
    }
}


