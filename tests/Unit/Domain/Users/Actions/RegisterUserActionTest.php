<?php

use App\Domain\Users\Actions\RegisterUserAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('creates a user with a hashed password', function () {
    $user = app(RegisterUserAction::class)->handle([
        'name' => 'Jane Buyer',
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->email)->toBe('jane@example.com')
        ->and(Hash::check('password123', $user->password))->toBeTrue();
});
