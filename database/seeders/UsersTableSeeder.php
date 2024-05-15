<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $usersData = [
            [
                'name' => 'Brian Omondi',
                'email' => 'brian.omondi@boxleocourier.com',
                'phone' => '254727611583',
                'password' => bcrypt('password'),
            ],

            [
                'name' => 'Regina Muna',
                'email' => 'business.development@boxleocourier.com',
                'phone' => '254728520152',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Maureen Nderitu',
                'email' => 'moreen.boxleo@gmail.com',
                'phone' => '254797919287',
                'password' => bcrypt('password'),
            ],

            [
                'name' => 'Mary Kinyanjui',
                'email' => 'Kinyajui.boxleo@gmail.com',
                'phone' => '254701692985',
                'password' => bcrypt('password'),
            ],

            [
                'name' => 'Eva Macrina',
                'email' => 'eva.musewe@boxleocourier.com',
                'phone' => '254111868048',
                'password' => bcrypt('password'),
            ],

            [
                'name' => 'Rono Douglas',
                'email' => 'itservices@boxleocourier.com',
                'phone' => '254110666140',
                'password' => bcrypt('password'),
            ],

            [
                'name' => 'John Mwacharo',
                'email' => 'john.boxleo@gmail.com',
                'phone' => '254741821113',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($usersData as $userData) {
            User::create($userData);
        }
    }
}