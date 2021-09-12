<?php

namespace Tests\Feature\Api\v1;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_logged_in()
    {
        $this->withoutExceptionHandling();
        $user = factory(Student::class)->create();
        $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['STUDENT'],
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

//    /** @test */
//    public function student_register()
//    {
//        try {
//            $this->withoutExceptionHandling();
//            $user = factory(Student::class)->create();
//            $this->post('api/v1/register', [
//                'name' => '$user->name',
//                'phone' => '+965406540654',
//                'password' => PASSWORD,
//                'type' => LOGIN_INFO_TYPES['STUDENT'],
//            ])->assertSuccessful()
//                ->assertJsonStructure([
//                    'success',
//                    'data' => [
//                        'id',
//                        'name',
//                        'username',
//                        'image',
//                        'phone',
//                        'email',
//                        'verified',
//                        'gender',
//                        'gender_name',
//                        'code',
//                        'lat',
//                        'lng',
//                        'dob',
//                        'created_at',
//                        'local',
//                        'notification',
//                        'unread_notifications',
//                        'access_token',
//                    ],
//                    'status',
//                    'message',
//                ]);
//        } catch (\Exception $exception) {
//            dd($exception);
//        }
//
//    }


//    /** @test */
//    public function teacher_register()
//    {
//        try {
//            $this->withoutExceptionHandling();
//            $user = factory(Student::class)->create();
//            $this->post('api/v1/register', [
//                'name' => '$user->name',
//                'phone' => '+965406540654',
//                'password' => PASSWORD,
//                'type' => LOGIN_INFO_TYPES['TEACHER'],
//            ])->assertSuccessful()
//                ->assertJsonStructure([
//                    'success',
//                    'data' => [
//                        'id',
//                        'name',
//                        'username',
//                        'image',
//                        'phone',
//                        'email',
//                        'verified',
//                        'gender',
//                        'gender_name',
//                        'code',
//                        'lat',
//                        'lng',
//                        'dob',
//                        'created_at',
//                        'local',
//                        'notification',
//                        'unread_notifications',
//                        'access_token',
//                    ],
//                    'status',
//                    'message',
//                ]);
//        } catch (\Exception $exception) {
////            dd($exception->getMessage());
//        }
//
//    }

    /** @test */
    public function teacher_logged_in()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
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

    /** @test */
    public function verified_code()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $this->post('api/v1/verified_code', [
            'phone' => $user->phone,
            'code' => '1234',
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
    }

    /** @test */
    public function forget_password()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $this->post('api/v1/forget_password', [
            'phone' => $user->phone,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
    }

    /** @test */
    public function change_password()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $logged_in_user = $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
        $access_token = json_decode($logged_in_user->getContent())->data->access_token;
        $this->post('api/v1/change_password', [
            'password' => '123456',
            'password_confirmation' => '123456',
        ], ['Authorization' => $access_token])->assertSuccessful();
    }

    /** @test */
    public function logout()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $logged_in_user = $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
        $access_token = json_decode($logged_in_user->getContent())->data->access_token;
        $this->post('api/v1/logout', [], ['Authorization' => $access_token])->assertSuccessful();
    }


}
