<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Authentication\EmailVerification;

use App\Support\Traits\CreateAModelFromFactory;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use URL;

class EmailVerifyTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;
    /**
     * @test
     */
    public function email_verify_user_successfully()
    {
        $user = User::factory()->unverified()->create();
        Sanctum::actingAs($user);

        $hashEmailUser = sha1($user->email);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => $hashEmailUser]
        );

        $response = $this->getJson(
            $verificationUrl
        );

        $response->assertOk();
    }
}
