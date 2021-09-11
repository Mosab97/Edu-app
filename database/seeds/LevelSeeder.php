<?php

use Illuminate\Database\Seeder;
use \App\Models\User;


class LevelSeeder extends Seeder
{
    public function run()
    {
        for ($item = 1; $item <= 3; $item++) \App\Models\Level::create([
            'name' => 'level ' . $item
        ]);
    }

}

