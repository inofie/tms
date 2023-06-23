<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
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

        // $link = url('/confirm_email').'?data='.$this->user->confirmation_code;
        // return (new MailMessage)
        //             ->subject('Checkd IN | Registration Confirmation Email')
        //             // ->view('emails.welcome_email', [
        //             //     'full_name'=> $this->user->full_name,
        //             //     'email' => $this->user->email,
        //             //     // 'user_mobile' => $this->user->user_mobile,
        //             //     'id' => $this->user->id
        //             // ]);
        //             ->greeting('Welcome! '.$this->user->first_name.' '.$this->user->last_name)
        //             ->line('You have been successfully registered on ' .config('app.name'). ' Click on below button to verify your account.')
        //             ->action('Verify Email', $link)
        //             ->line('Thank you for using our application!');

        $link = url('/confirm_email').'?data='.$this->user->confirmation_code;
        return (new MailMessage)
                    ->subject('Registration Confirmation Email - '.config('app.name'))
                    ->view('emails.welcome_email', [
                        'full_name'=> $this->user->full_name,
                        'email' => $this->user->email,
                        'id' => $this->user->id
                    ]);
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
