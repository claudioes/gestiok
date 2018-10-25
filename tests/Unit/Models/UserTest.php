<?php

namespace Tests\Unit\Models;

use Tests\UseDatabaseTrait;
use Tests\BaseTestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserTest extends BaseTestCase
{
    use UseDatabaseTrait;

    /** @test */
    public function a_user_can_have_fullname()
    {
        $user = new User([
            'firstname' => 'John',
            'lastname' => 'Doe'
        ]);

        $this->assertEquals('John Doe', $user->full_name);
    }
}