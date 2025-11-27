<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'userFirstName' => 'admin',
            'userLastName' => 'admin',
            'userEmail' => 'admin@gmail.com',
            'userPassword' => Hash::make('1122'),
            'userType' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
