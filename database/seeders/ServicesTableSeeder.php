<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $servicesData = [
            [
                'name' => 'Fulfillment',
            ],
            [
                'name' => 'Transport & Logistics',
            ],

            [
                'name' => 'Warehousing',
            ],
            [
              'name' => 'Transportation'
            ]
        ];

        foreach ($servicesData as $serviceData) {
            Service::create($serviceData);
        }
    }
}