<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(), // Default to verified
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'team_member',
            'is_active' => true,
            'verification_required' => false,
            'verification_token' => null,
            'verification_sent_at' => null,
            'first_login' => false,
            'force_password_change' => false,
            'password_change_required' => false,
            'login_count' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'is_active' => false,
            'verification_required' => true,
            'verification_token' => hash('sha256', Str::random(60)),
            'verification_sent_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'verification_required' => false,
        ]);
    }

    /**
     * Indicate that the user is a team member.
     */
    public function teamMember(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'team_member',
            'is_active' => false, // Team members need verification
            'email_verified_at' => null,
            'verification_required' => true,
        ]);
    }

    /**
     * Indicate that the user needs password change.
     */
    public function needsPasswordChange(): static
    {
        return $this->state(fn (array $attributes) => [
            'force_password_change' => true,
            'password_change_required' => true,
            'first_login' => true,
            'password_changed_at' => null,
        ]);
    }
}