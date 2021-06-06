<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function createUser()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
        ];

        return $user = User::create($userData);
    }
    public function test_me_with_success()
    {
        $user = $this->createUser();

        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/auth/me');

        $response->assertStatus(200);
    }
    public function test_me_with_no_login()
    {
        $response = $this->postJson('/api/auth/me');
        $response->assertStatus(401);
    }
}
