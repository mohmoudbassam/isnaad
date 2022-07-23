<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendTicketNotification implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public  $ticket;
    public  $message;
    public  $user;
    public  $store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket,$message,$store,$user)
    {

        $this->ticket=$ticket;
        $this->message=$message;
        $this->user=$user;
        $this->store=$store;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user.'.$this->user->id);
    }
}
