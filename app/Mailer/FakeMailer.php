<?php

namespace App\Mailer;

use App\Mailer\Contracts\MailerContract;
use App\Mailer\Contracts\MailableContract;

class FakeMailer implements MailerContract
{
    public function alwaysFrom(string $address, string $name = null)
    {
        //
    }

    public function to(string $address, string $name = null)
    {
        //
    }

    public function send(MailableContract $mail)
    {
        //
    }

    public function queue(MailableContract $mail)
    {
        //
    }
}
