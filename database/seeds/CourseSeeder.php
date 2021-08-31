<?php

use Illuminate\Database\Seeder;
use \App\Models\Course;


class CourseSeeder extends Seeder
{
    public function run()
    {
        for ($item = 1; $item <= 3; $item++) Course::create([
            'name' => 'Course' . $item,
        ]);
    }

}

