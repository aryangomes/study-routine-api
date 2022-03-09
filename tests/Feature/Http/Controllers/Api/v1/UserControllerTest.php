<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;
    private User $user;
    private string $uniqueUsername = 'uniqueUsername';
    private string $uniqueEmail = 'uniqueEmail@email.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->withMiddleware('auth:sanctum');
    }


    /**
     * @test
     * @dataProvider validatedDataToUpdateUser
     */
    public function update_user_successfully(array $validatedDataToUpdateUser, string $key)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'users.update',
                ['user' => $this->user],

            ),
            $validatedDataToUpdateUser
        );

        $response->assertStatus(200);

        $response->assertJsonPath($key, $validatedDataToUpdateUser[$key]);
    }

    /**
     * 
     * @test
     * @dataProvider invalidatedDataToUpdateUser
     */
    public function update_user_should_fail_because_data_is_not_valid(array $invalidatedDataToUpdateUser, string $key)
    {
        User::factory()->create(
            [
                'username' => $this->uniqueUsername,
                'email' => $this->uniqueEmail
            ]
        );

        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('users.update'),
            $invalidatedDataToUpdateUser
        );

        $response->assertUnprocessable();

        $this->assertNotEquals($invalidatedDataToUpdateUser[$key], $this->user->$key);
    }

    /**
     * @test
     */
    public function delete_user_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'users.destroy'
            )
        );
        $response->assertNoContent();

        $this->assertDeleted('users', $this->user->toArray());
    }

    /**
     * @test
     */
    public function delete_user_should_fail_because_user_is_unauthenticated()
    {

        $response = $this->deleteJson(
            route(
                'users.destroy'
            )
        );
        $response->assertUnauthorized();

        $this->assertTrue((User::find($this->user->id)) !== null);
    }



    public function validatedDataToUpdateUser(): array
    {
        return [

            'Update new name' => [['name' => $this->faker(User::class)->name()], 'name'],
            'Update new username' => [['username' => $this->faker(User::class)->userName()], 'username'],
            'Update new email' => [['email' => $this->faker(User::class)->safeEmail()], 'email'],


        ];
    }

    public function invalidatedDataToUpdateUser(): array
    {


        return [

            'New name is not string' => [
                ['name' => $this->faker(User::class)->randomDigit()], 'name'
            ],
            'New username is not string' => [
                ['username' => $this->faker(User::class)->randomDigit()], 'username'
            ],
            'New username is not unique' => [
                ['username' =>  $this->uniqueUsername], 'username'
            ],
            'New email is not email valid' => [
                ['email' => $this->faker(User::class)->title()], 'email'
            ],

            'New email is not unique' => [
                ['email' => $this->uniqueEmail], 'email'
            ],
            'New password is not string' => [
                [
                    'password' => 123456789,
                    'password_confirmation' => 123456789
                ], 'password'
            ],

            'New password must have a password confirmation' => [
                [
                    'password' => 'password',
                ], 'password'
            ],

            'New password does not match with password confirmation' => [
                [
                    'password' => 'password',
                    'password_confirmation' => 'ppassword'
                ], 'password'
            ],

        ];
    }
}
