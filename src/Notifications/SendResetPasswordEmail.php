<?php

namespace Elnooronline\LaravelApiAuthentication\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendResetPasswordEmail extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $code;

    /**
     * Create a new notification instance.
     *
     * @param $code
     */
    public function __construct( $code)
    {
        $this->code = $code;
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
        return (new MailMessage)
                    ->line(
                        trans('authentication::passwords.email-message', [
                            'code' => $this->code
                        ])
                    );
    }

}
