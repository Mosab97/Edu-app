<?php

use Illuminate\Database\Seeder;
use \App\Models\Course;


class CourseSeeder extends Seeder
{
    public function run()
    {
        for ($item = 1; $item <= 3; $item++) {
            $course = Course::create([
                'name' => 'Course' . $item,
            ]);
            for ($group = 1; $group <= 3; $group++)
                $course->groups()->create([
                    'name' => 'group ' . $group
                ]);
        }
    }

}

