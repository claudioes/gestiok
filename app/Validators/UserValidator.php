<?php

namespace App\Validators;

use App\Models\{
    User,
    Process
};
use App\Validators\Constraints as MyAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UserValidator extends Validator
{
    protected function getRules(): array
    {
        $id = $this->input('id');

        return [
            'email' => new Assert\Required([
                new Assert\Email,
                new MyAssert\Unique([
                    'query' => User::query(),
                    'field' => 'email',
                    'where' => ['id', '<>', $id]
                ]),
            ]),

            'name' => new Assert\Required([
                new Assert\NotBlank,
                new Assert\Length([
                    'min' => 4,
                    'max' => 190
                ]),
            ]),
        ];
    }
}
