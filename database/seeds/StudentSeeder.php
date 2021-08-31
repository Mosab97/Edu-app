<?php

use Illuminate\Database\Seeder;
use \App\Models\Student;


class StudentSeeder extends Seeder
{
    public function run()
    {
        $client = Student::create([
            'name' =>  'user',
            'email' => 'u@u.com',
            'username' => generateRandomString(7),
            'phone' => STUDENT_DEFAULT_PHONE,
            'whatsapp' => STUDENT_DEFAULT_PHONE,
            'verified' => true,
            'lat' => 31.5347908, //Indonesian hospital
            'lng' => 34.5102229,//Indonesian hospital
            'dob' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
        ]);
        for ($item = 1; $item <= 3; $item++) {
            $client = Student::create([
                'name' =>  'user' . $item,
                'username' => generateRandomString(7),
                'email' => 'user' . $item . '@u.com',
                'dob' => \Carbon\Carbon::now(),
                'verified' => true,
                'lat' => 31.5347908, //Indonesian hospital
                'lng' => 34.5102229,//Indonesian hospital
                'phone' => '+9665' . getRandomPhoneNumber_8_digit(),
                'password' => \Illuminate\Support\Facades\Hash::make(PASSWORD),
            ]);
        }
    }

}

