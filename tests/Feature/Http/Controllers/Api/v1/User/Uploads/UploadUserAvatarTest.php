<?php

namespace Tests\Feature\Http\Controllers\Api\v1\User\Uploads;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Support\Traits\CreateAModelFromFactory;
use Laravel\Sanctum\Sanctum;

class UploadUserAvatarTest extends TestCase
{
    use  RefreshDatabase, WithFaker, CreateAModelFromFactory;

    /**
     *      
     * @test
     */
    public function upload_user_avatar_successfully_in_register_user()
    {
        Storage::fake('local');

        $userDataToRegister = User::factory()->make()->toArray();

        $userDataToRegister = [
            ...$userDataToRegister,
            ...[
                'password' => 'password',
                'password_confirmation' => 'password',
                'user_avatar' => UploadedFile::fake()->create('file.jpg'),
            ]
        ];
        $response = $this->postJson(route('auth.register'), $userDataToRegister);

        $dataFromResponse = $response->getData();

        $pathToAssertUserAvatarImage = str_replace('storage', 'public', $dataFromResponse->user_avatar_path);

        $response->assertCreated();

        Storage::disk('local')->assertExists($pathToAssertUserAvatarImage);

        return $dataFromResponse;
    }

    /**
     *      
     * @test
     * 
     * @depends upload_user_avatar_successfully_in_register_user
     */
    public function upload_new_user_avatar_successfully_to_a_existing_user($dataFromResponse)
    {

        Storage::fake('local');
        $userAvatarPath = Storage::put(
            'public/user_images',
            UploadedFile::fake()->image('file.jpg')
        );


        $user = $this->createModelFromFactory(new User, [
            'user_avatar_path' => $userAvatarPath
        ]);

        Sanctum::actingAs($user);

        $dataToUploadUserAvatar = [
            'user_avatar' => UploadedFile::fake()->image('file.jpg'),
        ];

        $response = $this->patchJson(route('users.update', [
            'user' => $user
        ]), $dataToUploadUserAvatar);

        $dataFromResponse = $response->getData();

        $pathToAssertUserAvatarImage = str_replace('storage', 'public', $dataFromResponse->user_avatar_path);

        $response->assertOk();

        Storage::disk('local')->assertExists($pathToAssertUserAvatarImage);

        Storage::disk('local')->assertMissing($userAvatarPath);
    }
}
