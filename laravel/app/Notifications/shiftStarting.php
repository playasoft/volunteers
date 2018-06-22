<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Shift;
use App\Models\Slot;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;


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

      // Check if user is signed up for shift
      if ($this->shift->user_id == $this->user->id){

           return (new MailMessage)
                       ->subject('You Have a Shift Starting Soon!')
                       ->greeting('Hello '.$this->user->name.', you have a shift starting soon!')
                       ->line('Description: '.$this->shift->getDepartmentAttribute()->description)
                       ->action('View Shift',env('SITE_URL').'/slot/'.$this->shift->id.'/view')
                       ->line('This shift begins at '.$this->shift->start_time);
       }

       // If not, the notification is for the admin
       else {

           return (new MailMessage)
                       ->subject('There Is a Shift Starting Soon')
                       ->greeting('Hello '.$this->user->name.', no one has signed up for a shift that is starting soon!')
                       ->line('Description: '.$this->shift->getDepartmentAttribute()->description)
                       ->action('View Shift',env('SITE_URL').'/slot/'.$this->shift->id.'/view')
                       ->line('This shift begins at '.$this->shift->start_time);
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
