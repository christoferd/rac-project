<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $disk;
    public string $folder;
    public string $filename;

    /**
     * Create a new event instance.
     */
    public function __construct(string $disk, string $folder, string $filename)
    {
        $this->disk = $disk;
        $this->folder = $folder;
        $this->filename = $filename;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
