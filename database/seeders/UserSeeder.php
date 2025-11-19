<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@rmdc.com',
            'email_verified_at' => now(),
            'password' => Hash::make('users123'),
            'usertype' => 'admin',
            'bio' => 'System administrator for RMDC Dental Clinic',
            'avatar' => 'img/default-dp.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create  User
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('users123'),
            'usertype' => 'user',
            'bio' => 'Front desk staff at RMDC Dental Clinic',
            'avatar' => 'img/default-dp.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}