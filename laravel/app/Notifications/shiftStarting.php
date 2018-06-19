<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Shift;
use App\Models\Slot;
use App\Models\User;


class ShiftStarting extends Notification
{

   protected $shift;
   protected $user;

   use Queueable;

   /**
    * Create a new notification instance.
    *
    * @return void
    */
   public function __construct(Slot $shift, User $user)
   {
       $this->shift = $shift;
       $this->user = $user;
   }

   /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
   public function via($notifiable)
   {
       return ['mail'];
   }

   /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
   public function toMail($notifiable)
   {

       $urlShift = url('/slot/'.$this->shift->id.'/view');

       $urlEvent = url('/event/'.$this->shift->getEventAttribute()->id);

       // Check if the user is admin
       if ($this->user->id == 1) {
           return (new MailMessage)
                       ->subject('Hello, '.$this->user->name.' There is a Shift Starting Soon!')
                       ->greeting('Shift Starting Soon')
                       ->line('Uh Oh')
                       ->line('Description: '.$this->shift->getDepartmentAttribute()->description)
                       ->action('View Shift',env('SITE_URL').'/slot/'.$this->shift->id.'/view')
                       //->action('View Shift', $urlShift)
                       ->line('This shift begins: '.$this->shift->start_time);
       }
       else {

           return (new MailMessage)
                       ->subject('Hola '.$this->user->name.', You Have a Shift Starting Soon!')
                       ->greeting('You Have A Shift Starting Soon')
                       ->line('Description: '.$this->shift->getDepartmentAttribute()->description)
                       ->action('View Shift',env('SITE_URL').'/slot/'.$this->shift->id.'/view')
                       //->action('View Shift',$urlShift)
                       ->line('This shift begins: '.$this->shift->start_time);
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
