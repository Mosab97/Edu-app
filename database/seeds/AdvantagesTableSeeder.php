<?php

use Illuminate\Database\Seeder;

class AdvantagesTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 6; $i++)
            \App\Models\Advantage::create([
                'title' => [
                    'ar' => 'title ar' . $i,
                    'en' => 'title en' . $i,
                ],
                'details' => [
                    'ar' => 'details ar' . $i,
                    'en' => 'details en' . $i,
                ],
            ]);
    }
}
