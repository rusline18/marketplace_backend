<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Models\User;

class RegisterUserAction
{
    /**
     * Register a new buyer account.
     *
     * @param  array{name: string, email: string, password: string}  $data  Validated registration attributes.
     * @return User The newly created user.
     */
    public function handle(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
