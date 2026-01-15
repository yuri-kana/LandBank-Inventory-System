<?php
// app/Mail/PasswordResetMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;

    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->subject('Password Reset Request - Inventory System')
                    ->view('emails.password-reset')
                    ->with(['resetLink' => $this->resetLink]);
    }
}