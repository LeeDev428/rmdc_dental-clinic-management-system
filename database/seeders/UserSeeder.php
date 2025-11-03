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

        // Create Staff User
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

        // Create Doctor
        DB::table('users')->insert([
            'name' => 'Dr. Sarah Smith',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'usertype' => 'admin',
            'bio' => '',
            'avatar' => 'img/default-dp.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Regular Users
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'usertype' => 'user',
                'bio' => 'Regular clinic patient',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Social Auth Users
        DB::table('users')->insert([
            'name' => 'Google User',
            'email' => 'google.user@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(24)),
            'auth_provider' => 'google',
            'auth_provider_id' => '12345678901234',
            'usertype' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Facebook User',
            'email' => 'facebook.user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(24)),
            'auth_provider' => 'facebook',
            'auth_provider_id' => '987654321098',
            'usertype' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}