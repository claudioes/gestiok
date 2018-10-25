<?php

namespace App\Mailer\Contracts;

use App\Mailer\MessageBuilder;
use App\Mailer\Mailer;

interface MailableContract
{
    public function buildMessage(MessageBuilder $message);
}
