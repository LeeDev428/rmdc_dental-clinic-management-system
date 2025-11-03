<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs (patients)
        $userIds = DB::table('users')->where('usertype', 'user')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $services = [
            'Dental Cleaning',
            'Tooth Extraction',
            'Root Canal',
            'Teeth Whitening',
            'Dental Check-up',
            'Cavity Filling',
            'Crown Installation',
            'Orthodontic Consultation',
            'Gum Treatment',
            'Dental X-Ray',
        ];

        $appointments = [
            // Completed appointments
            [
                'user_id' => $userIds[0] ?? null,
                'title' => 'Root Canal Treatment',
                'procedure' => 'Root Canal',
                'duration' => 60,
                'time' => '09:00:00',
                'start' => Carbon::now()->subMonths(3)->setTime(9, 0, 0),
                'end' => Carbon::now()->subMonths(3)->setTime(10, 0, 0),
                'status' => 'completed',
            ],
            [
                'user_id' => $userIds[0] ?? null,
                'title' => 'Crown Installation',
                'procedure' => 'Crown Installation',
                'duration' => 45,
                'time' => '10:00:00',
                'start' => Carbon::now()->subMonths(2)->setTime(10, 0, 0),
                'end' => Carbon::now()->subMonths(2)->setTime(10, 45, 0),
                'status' => 'completed',
            ],
            [
                'user_id' => $userIds[1] ?? null,
                'title' => 'Dental Cleaning',
                'procedure' => 'Dental Cleaning',
                'duration' => 30,
                'time' => '14:00:00',
                'start' => Carbon::now()->subMonths(1)->setTime(14, 0, 0),
                'end' => Carbon::now()->subMonths(1)->setTime(14, 30, 0),
                'status' => 'completed',
            ],
            [
                'user_id' => $userIds[2] ?? null,
                'title' => 'Wisdom Tooth Extraction',
                'procedure' => 'Tooth Extraction',
                'duration' => 60,
                'time' => '11:00:00',
                'start' => Carbon::now()->subWeeks(3)->setTime(11, 0, 0),
                'end' => Carbon::now()->subWeeks(3)->setTime(12, 0, 0),
                'status' => 'completed',
            ],
            [
                'user_id' => $userIds[3] ?? null,
                'title' => 'Professional Whitening',
                'procedure' => 'Teeth Whitening',
                'duration' => 60,
                'time' => '15:00:00',
                'start' => Carbon::now()->subWeeks(2)->setTime(15, 0, 0),
                'end' => Carbon::now()->subWeeks(2)->setTime(16, 0, 0),
                'status' => 'completed',
            ],
            [
                'user_id' => $userIds[4] ?? null,
                'title' => 'Composite Filling',
                'procedure' => 'Cavity Filling',
                'duration' => 45,
                'time' => '13:00:00',
                'start' => Carbon::now()->subDays(10)->setTime(13, 0, 0),
                'end' => Carbon::now()->subDays(10)->setTime(13, 45, 0),
                'status' => 'completed',
            ],

            // Confirmed appointments (upcoming)
            [
                'user_id' => $userIds[5] ?? null,
                'title' => 'Regular Check-up',
                'procedure' => 'Dental Check-up',
                'duration' => 30,
                'time' => '09:00:00',
                'start' => Carbon::now()->addDays(2)->setTime(9, 0, 0),
                'end' => Carbon::now()->addDays(2)->setTime(9, 30, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $userIds[6] ?? null,
                'title' => 'Prophylaxis',
                'procedure' => 'Dental Cleaning',
                'duration' => 30,
                'time' => '10:30:00',
                'start' => Carbon::now()->addDays(3)->setTime(10, 30, 0),
                'end' => Carbon::now()->addDays(3)->setTime(11, 0, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $userIds[7] ?? null,
                'title' => 'Filling Replacement',
                'procedure' => 'Cavity Filling',
                'duration' => 45,
                'time' => '14:00:00',
                'start' => Carbon::now()->addDays(5)->setTime(14, 0, 0),
                'end' => Carbon::now()->addDays(5)->setTime(14, 45, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $userIds[8] ?? null,
                'title' => 'Braces Consultation',
                'procedure' => 'Orthodontic Consultation',
                'duration' => 30,
                'time' => '11:00:00',
                'start' => Carbon::now()->addWeeks(1)->setTime(11, 0, 0),
                'end' => Carbon::now()->addWeeks(1)->setTime(11, 30, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $userIds[9] ?? null,
                'title' => 'Root Canal Therapy',
                'procedure' => 'Root Canal',
                'duration' => 90,
                'time' => '15:30:00',
                'start' => Carbon::now()->addWeeks(1)->addDays(2)->setTime(15, 30, 0),
                'end' => Carbon::now()->addWeeks(1)->addDays(2)->setTime(17, 0, 0),
                'status' => 'confirmed',
            ],

            // Pending appointments
            [
                'user_id' => $userIds[0] ?? null,
                'title' => 'Follow-up Check',
                'procedure' => 'Dental Check-up',
                'duration' => 20,
                'time' => '09:30:00',
                'start' => Carbon::now()->addWeeks(2)->setTime(9, 30, 0),
                'end' => Carbon::now()->addWeeks(2)->setTime(9, 50, 0),
                'status' => 'pending',
            ],
            [
                'user_id' => $userIds[1] ?? null,
                'title' => 'Periodontal Treatment',
                'procedure' => 'Gum Treatment',
                'duration' => 60,
                'time' => '13:00:00',
                'start' => Carbon::now()->addWeeks(2)->addDays(1)->setTime(13, 0, 0),
                'end' => Carbon::now()->addWeeks(2)->addDays(1)->setTime(14, 0, 0),
                'status' => 'pending',
            ],
            [
                'user_id' => $userIds[3] ?? null,
                'title' => 'Panoramic X-Ray',
                'procedure' => 'Dental X-Ray',
                'duration' => 15,
                'time' => '10:00:00',
                'start' => Carbon::now()->addWeeks(3)->setTime(10, 0, 0),
                'end' => Carbon::now()->addWeeks(3)->setTime(10, 15, 0),
                'status' => 'pending',
            ],
            [
                'user_id' => $userIds[5] ?? null,
                'title' => 'Crown Preparation',
                'procedure' => 'Crown Installation',
                'duration' => 60,
                'time' => '14:30:00',
                'start' => Carbon::now()->addWeeks(3)->addDays(2)->setTime(14, 30, 0),
                'end' => Carbon::now()->addWeeks(3)->addDays(2)->setTime(15, 30, 0),
                'status' => 'pending',
            ],

            // Cancelled appointments
            [
                'user_id' => $userIds[2] ?? null,
                'title' => 'Teeth Cleaning',
                'procedure' => 'Dental Cleaning',
                'duration' => 30,
                'time' => '11:00:00',
                'start' => Carbon::now()->subDays(5)->setTime(11, 0, 0),
                'end' => Carbon::now()->subDays(5)->setTime(11, 30, 0),
                'status' => 'cancelled',
            ],
            [
                'user_id' => $userIds[4] ?? null,
                'title' => 'Whitening Session',
                'procedure' => 'Teeth Whitening',
                'duration' => 60,
                'time' => '16:00:00',
                'start' => Carbon::now()->addDays(1)->setTime(16, 0, 0),
                'end' => Carbon::now()->addDays(1)->setTime(17, 0, 0),
                'status' => 'cancelled',
            ],

            // More appointments for variety
            [
                'user_id' => $userIds[6] ?? null,
                'title' => 'Monthly Check-up',
                'procedure' => 'Dental Check-up',
                'duration' => 30,
                'time' => '09:00:00',
                'start' => Carbon::now()->addMonths(1)->setTime(9, 0, 0),
                'end' => Carbon::now()->addMonths(1)->setTime(9, 30, 0),
                'status' => 'pending',
            ],
            [
                'user_id' => $userIds[7] ?? null,
                'title' => 'Molar Root Canal',
                'procedure' => 'Root Canal',
                'duration' => 120,
                'time' => '13:30:00',
                'start' => Carbon::now()->addMonths(1)->addDays(5)->setTime(13, 30, 0),
                'end' => Carbon::now()->addMonths(1)->addDays(5)->setTime(15, 30, 0),
                'status' => 'pending',
            ],
            [
                'user_id' => $userIds[8] ?? null,
                'title' => 'Multiple Fillings',
                'procedure' => 'Cavity Filling',
                'duration' => 90,
                'time' => '10:30:00',
                'start' => Carbon::now()->addMonths(1)->addWeeks(2)->setTime(10, 30, 0),
                'end' => Carbon::now()->addMonths(1)->addWeeks(2)->setTime(12, 0, 0),
                'status' => 'pending',
            ],
        ];

        foreach ($appointments as $appointment) {
            if ($appointment['user_id']) {
                DB::table('appointments')->insert([
                    'user_id' => $appointment['user_id'],
                    'title' => $appointment['title'],
                    'procedure' => $appointment['procedure'],
                    'duration' => $appointment['duration'],
                    'time' => $appointment['time'],
                    'start' => $appointment['start'],
                    'end' => $appointment['end'],
                    'status' => $appointment['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
