<?php

use Illuminate\Database\Seeder;
use \App\Models\Student;


class TeacherSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Teacher::create([
            'name' => 'teacher',
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
            \App\Models\Teacher::create([
                'name' => 'teacher' . $item,
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


        foreach (\App\Models\Teacher::get() as $index => $item) {
            for ($group = 1; $group <= 3; $group++)
                $item->groups()->create([
                    'name' => 'group ' . $group,
                    'price' => $group,
                    'students_number_max' => $group,
                    'level' => collect(\App\Models\Group::levels)->random(),
                    'gender' => collect(Gender)->random(),
                    'course_id' => \App\Models\Course::get()->pluck('id')->random(),
                ]);
        }

    }

}

