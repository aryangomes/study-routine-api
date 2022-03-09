<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

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
        $response = $this->postJson(route('auth.register'), $userDataToRegister);

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
        $response = $this->postJson(
            route('auth.register'),
            $invalidDataToRegisterAUser
        );
        logger(
            get_class($this),
            [
                '$response->getData()' => $response->getData(),
            ]
        );
        $response->assertUnprocessable();

        $this->assertNull(User::where(
            'email',
            $userDataToRegister['email']
        )->first());
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
        $response = $this->postJson(
            route('auth.register'),
            $duplicateDataToRegister
        );

        $response->assertUnprocessable();
    }

    public function invalidDataToRegisterAUser(): array
    {

        return [

            'Name is required' => [
                collect($this->defaultData)->forget('name')->toArray()
            ],
            'Name is not string' => [
                collect($this->defaultData)->replace(
                    ['name' => $this->faker(User::class)->randomDigit()]
                )->toArray()
            ],
            'Username is required' => [
                collect($this->defaultData)->forget('username')->toArray()
            ], 'Username is not string' => [
                collect($this->defaultData)->replace(['username' => $this->faker(User::class)->randomDigit()])->toArray()
            ],   'Email is a invalid email' => [
                collect($this->defaultData)->replace(['email' => $this->faker(User::class)->title()])->toArray()
            ], 'Email is required' => [
                collect($this->defaultData)->forget('email')->toArray()
            ],

            'Password is required' => [
                collect($this->defaultData)->forget('password')->toArray()
            ], 'Password is not string' => [
                collect($this->defaultData)->replace(['password' => $this->faker(User::class)->randomDigit()])->toArray()
            ],

            'Password does not matched' => [
                collect($this->defaultData)->replace([
                    'password' => $this->faker(User::class)->title(),
                    'password_confirmation' => $this->faker(User::class)->text()
                ])->toArray()
            ],

            'Password Confirmation is required' => [
                collect($this->defaultData)->forget('password_confirmation')->toArray()
            ], 'Password Confirmation is not string' => [
                collect($this->defaultData)->replace(['password_confirmation' => $this->faker(User::class)->randomDigit()])->toArray()
            ],

        ];
    }

    public function duplicateDataToRegister()
    {
        return [

            'Username is not unique' => [
                collect($this->defaultData)->replace(['username' => $this->uniqueUsername])->toArray()
            ], 'Email is not unique' => [
                collect($this->defaultData)->replace(['email' => $this->uniqueEmail])->toArray()
            ],


        ];
    }
}
