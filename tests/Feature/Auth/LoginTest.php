<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $userToLogin;
    private array $credentialsToLogin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->credentialsToLogin =
            [
                'email' => 'email@email.com',
                'password' => 'password',
            ];

        User::factory()->create($this->credentialsToLogin);
    }

    /**
     * @test
     */
    public function login_user_successfully()
    {

        $response = $this->postJson(
            '/login',
            $this->credentialsToLogin
        );

        $response->assertStatus(200);
    }

    /**
     * @test
     * @dataProvider invalidCredentials
     */
    public function login_user_it_fails_with_invalid_credentials($invalidCredentials)
    {

        $response = $this->postJson(
            '/login',
            $invalidCredentials
        );

        $response->assertUnprocessable();
    }

    public function invalidCredentials(): array
    {

        $defaultCredentials = [
            'email' => 'email@email.com',
            'password' => 'password',
        ];

        return [
            'Email not exists' =>
            [
                collect($defaultCredentials)->replace(['email' => 'another@email.com'])->toArray()

            ],

            'Password incorrect' =>
            [
                collect($defaultCredentials)->replace(['password' => 'another_password'])->toArray()

            ],
        ];
    }
}
