<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Authentication\EmailVerification;

use App\Support\Traits\CreateAModelFromFactory;
use Domain\User\Models\User;
use Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResendEmailVerificationTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;
    /**
     * @test
     */
    public function resend_email_verification_user_successfully()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        Event::fake();

        $response = $this->postJson(
            route('verification.send')
        );

        $response->assertOk();

        Event::assertDispatched(Registered::class);
    }
}
