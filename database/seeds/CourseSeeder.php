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
            for ($q = 1; $q <= 3; $q++) {
                $question = $course->questions()->create([
                    'name' => 'question ' . $q
                ]);
                for ($a = 1; $a <= 3; $a++) $question->answers()->create([
                    'name' => 'Answer ' . $a,
                    'is_right_answer' => $a == 1,
                ]);

            }
        }
    }

}

