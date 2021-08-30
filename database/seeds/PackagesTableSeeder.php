<?php

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    private function createPackageValues(\App\Models\Package $package)
    {
        for ($i = 1; $i <= 3; $i++) \App\Models\PackageValues::create([
            'package_id' => $package->id,
            'value' => [
                'en' => 'value en ' . $i,
                'ar' => 'value ar ' . $i,
            ],
        ]);
    }

    private function createPackage($i)
    {
        $package = \App\Models\Package::create([
            'name' => [
                'ar' => 'Package name ar' . $i,
                'en' => 'Package name en' . $i,
            ],
            'price' => ($i * 100)
        ]);
        $this->createPackageValues($package);
        return $package;
    }

    public function run()
    {
        for ($i = 1; $i <= 3; $i++) $this->createPackage($i);
    }
}
