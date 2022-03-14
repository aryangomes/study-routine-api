<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


/**
 * @group authentication
 */
class LogoutTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $userToLogout;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userToLogout = User::factory()->create();
    }

    /**
     * 
     * @test
     */
    public function logout_user_successfully()
    {
        Sanctum::actingAs($this->userToLogout);

        $response = $this->getJson(route('auth.logout'));

        $response->assertOk();
    }

    /**
     * 
     * @test
     */
    public function logout_should_fail_because_user_is_not_authenticated()
    {

        $response = $this->getJson(route('auth.logout'));

        $response->assertUnauthorized();
    }
}
