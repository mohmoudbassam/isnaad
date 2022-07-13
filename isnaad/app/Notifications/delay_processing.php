<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class delay_processing extends Notification
{
    use Queueable;
    private $order;
    private $flag;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order,$flag)         //if flag = false be the first notifcaion else second notifcation
    {
        $this->order=$order;
        $this->flag=$flag;
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

        $order_number=$this->order->order_number;
        $dt = Carbon::now();
        $delayDay=$dt->diffInDays($this->order->shipping_date);
        if($this->flag){
            return (new SlackMessage)
                ->from('Isnaad-portal')
                ->content('order no : '.$order_number .' is  delayed for  3  days');
        }else{
            return (new SlackMessage)
                ->from('Isnaad-portal')
                ->content('order no : '.$order_number .' is  delayed for  24  hours');
        }

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
