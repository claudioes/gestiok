<?php

namespace App\Jobs;

use App\Queue\Contracts\ShouldQueue;
use App\Mailer\Mailer;
use App\Mailer\Mailable;

class SendMail implements ShouldQueue
{
    private $mail;

    public function __construct(Mailable $mail)
    {
        $this->mail = $mail;
    }
    
    public function handle(Mailer $mailer)
    {
        $mailer->send($this->mail);
    }
}