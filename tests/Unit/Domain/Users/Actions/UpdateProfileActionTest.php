<?php

use App\Domain\Users\Actions\UpdateProfileAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('updates the provided fields and leaves others untouched', function () {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

    $result = app(UpdateProfileAction::class)->handle($user, ['name' => 'New Name']);

    expect($result->name)->toBe('New Name')
        ->and($result->email)->toBe('old@example.com');
});

it('re-hashes the password only when one is provided', function () {
    $user = User::factory()->create();
    $originalHash = $user->password;

    $result = app(UpdateProfileAction::class)->handle($user, ['password' => 'new-password123']);

    expect($result->password)->not->toBe($originalHash)
        ->and(Hash::check('new-password123', $result->password))->toBeTrue();
});
