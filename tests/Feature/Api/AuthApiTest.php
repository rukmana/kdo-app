<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_mengembalikan_token_dan_token_bisa_dipakai(): void
    {
        User::factory()->create(['email' => 'admin@kdo.test', 'password' => 'password']);

        $token = $this->postJson('/api/login', ['email' => 'admin@kdo.test', 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'user' => ['id', 'name', 'email']])
            ->json('access_token');

        $this->withToken($token)->getJson('/api/me')->assertOk()->assertJsonPath('data.email', 'admin@kdo.test');
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->create(['email' => 'admin@kdo.test', 'password' => 'password']);

        $this->postJson('/api/login', ['email' => 'admin@kdo.test', 'password' => 'salah'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_endpoint_terlindungi_tanpa_token_mengembalikan_401(): void
    {
        $this->getJson('/api/kendaraan')->assertUnauthorized()->assertJsonStructure(['message']);
    }

    public function test_logout_mencabut_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
