<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class ChatMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatData; // Declare this property to hold the data

    /**
     * Create a new event instance.
     *
     * @param  array  $chatData
     * @return void
     */
    public function __construct($chatData)
    {
        $this->chatData = $chatData; // Assign the data to the public property
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chatchannel');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'chatData' => $this->chatData,  // Broadcast the chat data
        ];
    }
}
