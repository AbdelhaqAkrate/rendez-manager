<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Haq',
                'last_name' => 'ken',
                'email' => 'hak.ken@gmail.com',
                'phone' => '0611223344',
                'address' => '123 Main St',
                'country' => 'Marocco',
                'gender' => 'Male',
                'active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('123456'), 
                'last_connection' => now(),
                'start_date' => now(),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
