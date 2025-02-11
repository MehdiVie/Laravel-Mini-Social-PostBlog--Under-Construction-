<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    /**
     * Create a new event instance.
     */
    /*
    public function __construct($chat)
    {
        //
        $this->chat = ['username' => $chat['username'] , 
        'avatar' => $chat['avatar'] , 'textvalue' => $chat['textvalue']];
    }*/
    public function __construct($chat)
    {
        // Ensure all required keys exist in the $chat array
        $this->chat = array_merge([
            'username' => null,
            'avatar' => null,
            'textvalue' => null,
        ], $chat);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        \Log::info('Broadcasting event to chatchannel', ['chat' => $this->chat]);
        return new Channel('chatchannel');
    }
    

}
