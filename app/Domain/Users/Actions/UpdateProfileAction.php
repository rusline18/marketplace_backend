<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Models\User;

class UpdateProfileAction
{
    /**
     * Update the authenticated user's profile.
     *
     * @param  User  $user  The user to update.
     * @param  array{name?: string, email?: string, password?: string}  $data  Validated profile attributes.
     * @return User The updated user.
     */
    public function handle(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        if (isset($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return $user;
    }
}
