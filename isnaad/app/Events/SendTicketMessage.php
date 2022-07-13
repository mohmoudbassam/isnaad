<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendTicketMessage implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public  $ticket;
    public  $message;
    public  $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket,$message,$user)
    {

        $this->ticket=$ticket;
        $this->message=$message;
        $this->user=$user;
        $this->dontBroadcastToCurrentUser();

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ticket.'.$this->ticket->id);
    }
}
