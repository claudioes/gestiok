<?php

namespace App\Mailer\Contracts;

interface MailerContract
{
    public function alwaysFrom(string $address, string $name = null);
    public function to($address, string $name = null);
    public function send(MailableContract $mail);
    public function queue(MailableContract $mail);
}