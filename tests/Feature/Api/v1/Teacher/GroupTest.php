<?php

namespace Tests\Feature\Api\v1\Teacher;

use App\Models\Age;
use App\Models\Course;
use App\Models\Group;
use App\Models\Level;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_get_my_groups()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $access_token = $this->getToken($user);
        $this->get('api/v1/teacher/my_groups', ['Authorization' => $access_token])->assertSuccessful();
    }


    /** @test */
    public function teacher_get_single_group_by_id()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $access_token = $this->getToken($user);
        $group = factory(Group::class)->create();
        $this->get('api/v1/teacher/group/' . $group->id, ['Authorization' => $access_token])->assertSuccessful();
    }

    /** @test */
    public function teacher_get_single_group_by_course_id()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $access_token = $this->getToken($user);
        $course = factory(Course::class)->create();
        $this->get('api/v1/teacher/groups_course_id/' . $course->id, ['Authorization' => $access_token])->assertSuccessful();
    }

    /** @test */
    public function teacher_can_add_group()
    {
        $this->withoutExceptionHandling();
        $user = factory(Teacher::class)->create();
        $access_token = $this->getToken($user);
        $course = factory(Course::class)->create();
        $level = factory(Level::class)->create();
        $age = factory(Age::class)->create();
        $this->post('api/v1/teacher/add_group',[
            'name' => 'sdsd',
            'course_id' => $course->id,
            'price' => 56,
            'level_id' => $level->id,
            'age_id' => $age->id,
            'students_number_max' => 65,
            'number_of_live_lessons' => 65,
            'number_of_exercises_and_games' => 6,
            'course_date_and_time' => Carbon::now()->format('Y-m-d H:i'),
        ], ['Authorization' => $access_token])->assertSuccessful();
    }
}
