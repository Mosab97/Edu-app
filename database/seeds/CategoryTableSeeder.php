<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{

    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            $category = \App\Models\Category::create([
                'name' => [
                    'ar' => 'category' . $i,
                    'en' => 'category' . $i,
                ],
            ]);
        }
    }
}
