<?php

namespace App\Mail\ResetPassword;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class ResetPasswordEmail extends Mailable implements ShouldQueue
{

    public function __construct(
        private string $email,
        private string $token
    ) {
        $this->initializeServices();
        $this->setModels();
    }

    private function initializeServices(): void
    {
    }


    private function setModels(): void
    {
    }

    public function __sleep()
    {
        return ['email', 'token'];
    }

    public function __wakeup()
    {
        $this->initializeServices();
        $this->setModels();
    }

    public function build(): ResetPasswordEmail
    {
        $subject = 'Reset Password';

        return $this
            ->from(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            )
            ->to($this->email)
            ->view('emails.forget-password')
            ->subject($subject)
            ->with(
                [
                    'token' => $this->token,
                ]
            );
    }
}
