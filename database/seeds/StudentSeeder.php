<?php

use Illuminate\Database\Seeder;


class StudentSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\User::class, 10)->create(['user_type' => \App\Models\User::user_type['STUDENT']])->each(function ($student) {
            $teachersCount = \App\Models\User::query()->where('user_type', \App\Models\User::user_type['TEACHER'])->count();
            if ($student->id == ($teachersCount + 1)) $student->update(['phone' => STUDENT_DEFAULT_PHONE]);;
            $course = factory(\App\Models\Course::class)->create();
            $group = factory(\App\Models\Group::class)->create();
            $student->student_groups()->create([
                'course_id' => $course->id,
                'group_id' => $group->id,
            ]);
        });
    }

}

