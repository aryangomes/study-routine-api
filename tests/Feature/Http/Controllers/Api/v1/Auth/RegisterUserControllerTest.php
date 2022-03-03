<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
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
                'password' => '::some_password::',
                'password_confirmation' => '::some_password::',
            ]
        ];
        // dd($userDataToRegister);
        $response = $this->postJson('/register', $userDataToRegister);

        $response->assertStatus(201);
    }
}
