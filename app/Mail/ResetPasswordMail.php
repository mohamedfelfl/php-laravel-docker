<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $name = null;

    public function __construct(string $name)
    {
        $this->$name = $name;
    }

    public function build()
    {
        return $this->view('emails.reset_password');
    }
}
