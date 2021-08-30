<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(StatisticsTableSeeder::class);
        $this->call(AdvantagesTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(FaqTableSeeder::class);
        $this->call(CustomerReviewsTableSeeder::class);
        $this->call(BlogTableSeeder::class);
        $this->call(ManagerTableSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CategoryTableSeeder::class);

        $this->call(SettingsSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(ISeedOauthPersonalAccessClientsTableSeeder::class);
        $this->call(ISeedOauthClientsTableSeeder::class);

    }
}
