<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use  RefreshDatabase;

    private string $uniqueEmail = "unique@email.com";
    private string $uniqueUsername = "uniqueUsername";

    private array $defaultData =
    [
        'name' => 'name',
        'username' => 'username',
        'email' => 'email@email.com',
        'password' => 'password',
        'password_confirmation' =>  'password',
    ];

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

        $this->assertEquals($userDataToRegister['email'], User::find($response->getData()->id)->email);
    }

    /**
     * @test
     * @dataProvider invalidDataToRegisterAUser
     */
    public function user_register_it_fails_with_invalid_data($invalidDataToRegisterAUser)
    {
        $userDataToRegister = User::factory()->make(
            [
                'email' => $this->uniqueEmail,
                'username' => $this->uniqueUsername,
            ]
        );
        $response = $this->postJson('/register', $invalidDataToRegisterAUser);

        $response->assertUnprocessable();

        $this->assertNull(User::where('email', $userDataToRegister['email'])->first());
    }

    /**
     * @test
     * @dataProvider duplicateDataToRegister
     */
    public function user_register_it_fails_because_user_already_exists($duplicateDataToRegister)
    {
        $userDataToRegister = User::factory()->create(
            [
                'email' => $this->uniqueEmail,
                'username' => $this->uniqueUsername,
            ]
        );
        $response = $this->postJson('/register', $duplicateDataToRegister);

        $response->assertUnprocessable();
    }

    public function invalidDataToRegisterAUser(): array
    {


        $notString = 12345;

        $invalidEmail = 'invalid.email';

        return [

            'Name required' => [
                collect($this->defaultData)->forget('name')->toArray()
            ],
            'Name not string' => [
                collect($this->defaultData)->replace(['name' => $notString])->toArray()
            ],
            'Username required' => [
                collect($this->defaultData)->forget('username')->toArray()
            ], 'Username not string' => [
                collect($this->defaultData)->replace(['username' => $notString])->toArray()
            ],   'Email invalid' => [
                collect($this->defaultData)->replace(['email' => $invalidEmail])->toArray()
            ], 'Email required' => [
                collect($this->defaultData)->forget('email')->toArray()
            ],

            'Password required' => [
                collect($this->defaultData)->forget('password')->toArray()
            ], 'Password string' => [
                collect($this->defaultData)->replace(['password' => $notString])->toArray()
            ],

            'Password confirmed' => [
                collect($this->defaultData)->replace(['password_confirmation' => $notString])->toArray()
            ],

            'Password Confirmation required' => [
                collect($this->defaultData)->forget('password_confirmation')->toArray()
            ], 'Password Confirmation string' => [
                collect($this->defaultData)->replace(['password_confirmation' => $notString])->toArray()
            ],

        ];
    }

    public function duplicateDataToRegister()
    {
        return [

            'Username unique' => [
                collect($this->defaultData)->replace(['username' => $this->uniqueUsername])->toArray()
            ], 'Email unique' => [
                collect($this->defaultData)->replace(['email' => $this->uniqueEmail])->toArray()
            ],


        ];
    }
}
