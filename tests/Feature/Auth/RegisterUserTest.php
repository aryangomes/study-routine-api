<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use  RefreshDatabase;

    private $uniqueEmail = "unique@email.com";
    private $uniqueUsername = "uniqueUsername";

    /**
     *      
     * @test
     */
    public function register_user_successfully()
    {
        $userDataToRegister = User::factory()->make()->toArray();
        $userDataToRegister = [
            ...$userDataToRegister,
            ...[
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        ];
        $response = $this->postJson('/register', $userDataToRegister);

        $response->assertStatus(201);
    }

    /**
     * @test
     * @dataProvider invalidDataToRegisterAUser
     */
    public function user_register_it_fails_with_invalid_data($invalidDataToRegisterAUser)
    {
        $userDataToRegister = User::factory()->create(
            [
                'email' => $this->uniqueEmail,
                'username' => $this->uniqueUsername,
            ]
        );
        $response = $this->postJson('/register', $invalidDataToRegisterAUser);

        $response->assertUnprocessable();
    }

    public function invalidDataToRegisterAUser(): array
    {
        $defaultData =
            [
                'name' => 'name',
                'username' => 'username',
                'email' => 'email@email.com',
                'password' => 'password',
                'password_confirmation' =>  'password',
            ];

        $notString = 12345;

        $invalidEmail = 'invalid.email';

        return [

            'Name required' => [
                collect($defaultData)->forget('name')->toArray()
            ],
            'Name not string' => [
                collect($defaultData)->replace(['name' => $notString])->toArray()
            ],
            'Username required' => [
                collect($defaultData)->forget('username')->toArray()
            ], 'Username not string' => [
                collect($defaultData)->replace(['username' => $notString])->toArray()
            ],  'Username unique' => [
                collect($defaultData)->replace(['username' => $this->uniqueUsername])->toArray()
            ], 'Email invalid' => [
                collect($defaultData)->replace(['email' => $invalidEmail])->toArray()
            ], 'Email required' => [
                collect($defaultData)->forget('email')->toArray()
            ], 'Email unique' => [
                collect($defaultData)->replace(['email' => $this->uniqueEmail])->toArray()
            ],

            'Password required' => [
                collect($defaultData)->forget('password')->toArray()
            ], 'Password string' => [
                collect($defaultData)->replace(['password' => $notString])->toArray()
            ],

            'Password confirmed' => [
                collect($defaultData)->replace(['password_confirmation' => $notString])->toArray()
            ],

            'Password Confirmation required' => [
                collect($defaultData)->forget('password_confirmation')->toArray()
            ], 'Password Confirmation string' => [
                collect($defaultData)->replace(['password_confirmation' => $notString])->toArray()
            ],

        ];
    }
}
