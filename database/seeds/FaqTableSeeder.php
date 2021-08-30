<?php

use Illuminate\Database\Seeder;

class FaqTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 6; $i++) \App\Models\Faq::create([
            'key' => [
                'ar' => 'Faq name ar' . $i,
                'en' => 'Faq name en' . $i,
            ],
            'value' => [
                'ar' => 'value name ar' . $i,
                'en' => 'value name en' . $i,
            ],
        ]);
    }
}
