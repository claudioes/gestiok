<?php

namespace App\Validators;

use App\Validators\Constraints as MyAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UserPasswordValidator extends Validator
{
    protected function getRules(): array
    {
        return [
            'password' => new Assert\Required([
                new Assert\NotBlank,
                new Assert\Length([
                    'min' => 4,
                    'max' => 190
                ]),
            ]),
            
            'password_confirmation' => new Assert\Required([
                new Assert\NotBlank,
                new MyAssert\Confirmation([
                    'key' => 'password',
                ]),
            ]),
        ];
    }
}
