<?php

namespace Tests\Feature\Api\v1;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
//    use RefreshDatabase;

    /** @test */
    public function student_logged_in()
    {
        $this->withoutExceptionHandling();
        $this->passportAs($user = factory(User::class)->create())
            ->post('api/v1/login', [
                'phone' => STUDENT_DEFAULT_PHONE,
                'password' => PASSWORD,
            ])->assertSuccessful()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'username',
                    'image',
                    'phone',
                    'email',
                    'verified',
                    'gender',
                    'gender_name',
                    'code',
                    'lat',
                    'lng',
                    'dob',
                    'created_at',
                    'local',
                    'notification',
                    'unread_notifications',
                    'access_token',
                ],
                'status',
                'message',
            ]);
    }
}
