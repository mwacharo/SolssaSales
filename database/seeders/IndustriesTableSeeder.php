<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;

class IndustriesTableSeeder extends Seeder
{
    public function run()
    {
        $industriesData = [
            'E-commerce',
            'Pharmaceuticals',
            'Marketing and Advertising',
            'Healthcare',
            'Cosmetics',
            'Health, Wellness and Fitness',
            'Food and Beverage',
            'Automotive',
            'Entertainment',
        ];

        foreach ($industriesData as $industryName) {
            Industry::create(['name' => $industryName]);
        }
    }
}