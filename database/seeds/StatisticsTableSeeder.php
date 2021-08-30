<?php

use Illuminate\Database\Seeder;

class StatisticsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 4; $i++) \App\Models\Statistic::create([
            'key' => 'key' . $i,
            'value' => ($i * 200),
        ]);
    }
}
