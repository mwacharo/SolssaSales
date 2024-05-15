<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    public function run()
    {
        $branchesData = [
            [
                'name' => 'Kenya',
                'headquarters' => 'Main Headquarters',
                'phone' => '1234567890',
                'email' => 'info@boxleocourier.com',
            ],
            [
                'name' => 'Uganda',
                'headquarters' => 'Kampala',
                'phone' => '9876543210',
                'email' => 'uganda@boxleocourier.com',
            ],
            [
                'name' => 'Tanzania',
                'headquarters' => 'Dar esalaam',
                'phone' => '5555555555',
                'email' => 'tanzania@boxleocourier.com',
            ],
        ];

        foreach ($branchesData as $branchData) {
            Branch::create($branchData);
        }
    }
}