<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Auth;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group authentication
 */
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

        $response->assertOk();
    }

    /**
     * @test
     * @dataProvider invalidCredentials
     */
    public function login_user_should_fail_because_credentials_are_not_valid($invalidCredentials)
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
