<?php

namespace Elnooronline\LaravelApiAuthentication\Listeners;

use Illuminate\Support\Facades\Notification;
use Elnooronline\LaravelApiAuthentication\Events\ResetPasswordCodeGenerated;
use Elnooronline\LaravelApiAuthentication\Notifications\SendResetPasswordEmail;

class ResetPasswordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Elnooronline\LaravelApiAuthentication\Events\ResetPasswordCodeGenerated  $event
     * @return void
     */
    public function handle(ResetPasswordCodeGenerated $event)
    {
        Notification::send(
            $event->user,
            new SendResetPasswordEmail($event->code)
        );
    }
}
