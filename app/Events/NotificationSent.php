<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;
    public $notification;

    public function __construct($userId, $notification)
    {
        $this->userId = $userId;
        $this->notification = $notification;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('notifications-' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'notification-sent';
    }

    public function broadcastWith()
    {
        return $this->notification;
    }
}
