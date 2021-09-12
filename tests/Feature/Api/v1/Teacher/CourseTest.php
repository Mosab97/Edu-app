<?php

namespace Tests\Feature\Api\v1\Teacher;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_get_courses()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $logged_in_user = $this->post('api/v1/login', [
            'phone' => $user->phone,
            'password' => PASSWORD,
            'type' => LOGIN_INFO_TYPES['TEACHER'],
        ])->assertSuccessful();
        $access_token = json_decode($logged_in_user->getContent())->data->access_token;
        $this->get('api/v1/teacher/courses', ['Authorization' => $access_token])->assertSuccessful();
    }
}
