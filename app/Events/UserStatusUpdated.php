<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;
    public $userType;
    public $isActive;
    public $lastActiveAt;

    public function __construct($userId, $userType, $isActive, $lastActiveAt)
    {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->isActive = $isActive;
        $this->lastActiveAt = $lastActiveAt;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user-status');
    }

    public function broadcastAs(): string
    {
        return 'user-status-updated';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'user_type' => $this->userType,
            'is_active' => $this->isActive,
            'last_active_at' => $this->lastActiveAt ? $this->lastActiveAt->format('Y-m-d H:i') : null,
        ];
    }
}