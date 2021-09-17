<?php

use Illuminate\Database\Seeder;


class TeacherSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'name' => 'teacher',
            'user_type' => \App\Models\User::user_type['TEACHER'],
            'email' => 'teacher@teacher.com',
            'username' => generateRandomString(7),
            'phone' => TEACHER_DEFAULT_PHONE,
            'whatsapp' => STUDENT_DEFAULT_PHONE,
            'verified' => true,
            'lat' => 31.5347908, //Indonesian hospital
            'lng' => 34.5102229,//Indonesian hospital
            'dob' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
        ]);
        for ($item = 1; $item <= 3; $item++) {
            \App\Models\User::create([
                'name' => 'teacher' . $item,
                'major' => 'teacher' . $item,
                'user_type' => \App\Models\User::user_type['TEACHER'],
                'username' => generateRandomString(7),
                'email' => 'teacher' . $item . '@teacher.com',
                'dob' => \Carbon\Carbon::now(),
                'verified' => true,
                'lat' => 31.5347908, //Indonesian hospital
                'lng' => 34.5102229,//Indonesian hospital
                'phone' => '+9665' . getRandomPhoneNumber_8_digit(),
                'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
            ]);
        }


        foreach (\App\Models\User::teacher()->get() as $index => $item) {
            for ($group = 1; $group <= 3; $group++)
                $item->teacher_groups()->create([
                    'name' => 'group ' . $group,
                    'price' => $group,
                    'students_number_max' => $group,
                    'number_of_live_lessons' => $group,
                    'number_of_exercises_and_games' => $group,
                    'course_date_and_time' => \Carbon\Carbon::now(),
                    'what_will_i_learn' => 'what_will_i_learn',
                    'course_id' => \App\Models\Course::get()->pluck('id')->random(),
                    'level_id' => \App\Models\Level::get()->pluck('id')->random(),
                    'age_id' => \App\Models\Age::get()->pluck('id')->random(),
                    'gender' => collect(Gender)->random(),
                ]);
        }

    }

}

