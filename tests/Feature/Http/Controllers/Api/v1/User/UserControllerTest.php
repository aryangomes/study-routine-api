<?php

namespace Tests\Feature\Http\Controllers\Api\v1\User;

use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;
    private User $user;
    private string $uniqueUsername = 'uniqueUsername';
    private string $uniqueEmail = 'uniqueEmail@email.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createModelFromFactory(new User);

        $this->withMiddleware(['auth:sanctum', 'verified']);
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

        $this->createModelFromFactory(new User, [
            'username' => $this->uniqueUsername,
            'email' => $this->uniqueEmail
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('users.update'),
            $invalidatedDataToUpdateUser
        );

        $response->assertUnprocessable();

        $this->assertNotEquals($invalidatedDataToUpdateUser[$key], $this->user->$key);
    }
    /**
     * 
     * @test
     */
    public function show_data_user_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'users.show'
            )
        );
        $response->assertOk();

        $this->assertEquals($this->user->id, $response->getData()->id);
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
     * @dataProvider routesResourceWithAuthentication
     */
    public function user_cannot_access_route_because_its_unauthenticated($route, $method)
    {
        $methodJson = $method . "Json";

        $response = $this->$methodJson(
            route($route)
        );

        $response->assertUnauthorized();
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

    public function userRoutesResource(): array
    {
        return [
            'Show user' => ['users.show', 'get'],
            'Update user' => ['users.update', 'patch'],
            'Delete user' => ['users.destroy', 'delete'],
        ];
    }

    public function routesResourceWithEmailVerified(): array
    {
        $modelName = 'user';
        return  [
            "User cannot view {$modelName} because is not verified" => ["{$modelName}s.show", 'get'],
            "User cannot update {$modelName} because is not verified" => ["{$modelName}s.update", 'patch'],
            "User cannot delete {$modelName} because is not verified" => ["{$modelName}s.destroy", 'delete'],
        ];
    }

    public function routesResourceWithAuthentication(): array
    {
        $modelName = 'user';
        return [
            "User cannot view {$modelName} because is not authenticated" => ["{$modelName}s.show", 'get'],
            "User cannot update {$modelName} because is not authenticated" => ["{$modelName}s.update", 'patch'],
            "User cannot delete {$modelName} because is not authenticated" => ["{$modelName}s.destroy", 'delete'],
        ];
    }

    public function routesResourceWithPolicies(): array
    {
        $modelName = 'user';
        return [
            "User cannot view {$modelName}" => ["{$modelName}s.show", 'get'],
            "User cannot update {$modelName}" => ["{$modelName}s.update", 'patch'],
            "User cannot delete {$modelName}" => ["{$modelName}s.destroy", 'delete'],
        ];
    }
}
