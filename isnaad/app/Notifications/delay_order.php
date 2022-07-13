<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class delay_order extends Notification
{
    use Queueable;
    private $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order=$order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSlack($notifiable)
    {
        $tr_link=$this->order->carriers->tracking_link.$this->order->tracking_number;
        $tr=$this->order->tracking_number;
        $order_number=$this->order->order_number;
        $dt = Carbon::now();
        $delayDay=$dt->diffInDays($this->order->shipping_date);
        return (new SlackMessage)
            ->from('Isnaad-portal')
            ->content('order no'." $order_number".' '.'tracking num # is '."$tr".'<'." $tr_link".' |>'.' delayed for '."$delayDay".' days  ');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
