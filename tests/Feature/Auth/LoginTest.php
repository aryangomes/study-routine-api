<?php

namespace Tests\Feature\Auth;

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
            route('auth.login'),
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
            route('auth.login'),
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
            'Email is not exists' =>
            [
                collect($defaultCredentials)->replace(['email' => 'another@email.com'])->toArray()

            ],

            'Password is incorrect' =>
            [
                collect($defaultCredentials)->replace(['password' => 'another_password'])->toArray()

            ],
        ];
    }
}
