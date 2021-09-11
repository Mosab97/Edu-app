<?php

use Illuminate\Database\Seeder;
use \App\Models\User;


class AgeSeeder extends Seeder
{
    public function run()
    {
        for ($item = 1; $item <= 3; $item++) \App\Models\Age::create([
            'name' => 'age ' . $item
        ]);
    }

}

