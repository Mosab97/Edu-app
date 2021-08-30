<?php

use Illuminate\Database\Seeder;
use \App\Models\User;


class ClientSeeder extends Seeder
{
    public function run()
    {
        $client = User::create([
            'name' => [
                'ar' => 'user',
                'en' => 'user',
            ],
            'email' => 'u@u.com',
            'username' => generateRandomString(7),
            'phone' => PHONE_CLIENT1,
            'whatsapp' => PHONE_CLIENT1,
            'verified' => true,
            'is_registered' => true,
            'lat' => 31.5347908, //Indonesian hospital
            'lng' => 34.5102229,//Indonesian hospital
            'dob' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make('u@u.com'),
        ]);
        for ($item = 1; $item <= 3; $item++) {
            $client = User::create([
                'name' => [
                    'ar' => 'user' . $item,
                    'en' => 'user' . $item,
                ],
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

