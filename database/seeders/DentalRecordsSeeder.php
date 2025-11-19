<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DentalRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs (patients)
        $userIds = DB::table('users')->where('usertype', 'user')->pluck('id')->toArray();
        
        // Get dentist IDs
        $dentistIds = DB::table('users')->where('usertype', 'admin')->pluck('id')->toArray();

        if (empty($userIds) || empty($dentistIds)) {
            $this->command->warn('No users or dentists found. Please run UserSeeder first.');
            return;
        }

        $dentalRecords = [
            // Records for first few patients
            [
                'user_id' => $userIds[1] ?? null,
                'dentist_id' => $dentistIds[0] ?? null,
                'visit_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'chief_complaint' => 'Toothache on upper right molar',
                'medical_history' => 'No significant medical history.',
                'current_medications' => 'None',
                'allergies' => 'No known allergies',
                'blood_pressure' => '120/80',
                'oral_examination' => 'Good overall oral health, isolated cavity detected',
                'gum_condition' => 'Healthy gums, no inflammation',
                'tooth_condition' => 'Deep cavity on tooth #14',
                'xray_findings' => 'Deep carious lesion extending to pulp',
                'diagnosis' => 'Deep cavity on tooth #14 with pulp involvement',
                'treatment_plan' => 'Root canal treatment followed by crown placement',
                'treatment_performed' => 'Root canal treatment and temporary filling',
                'teeth_numbers' => '14',
                'prescription' => 'Amoxicillin 500mg TID for 7 days, Mefenamic Acid 500mg TID as needed',
                'recommendations' => 'Avoid hard foods, maintain good oral hygiene',
                'next_visit' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'notes' => 'Patient advised to avoid hard foods. Follow-up for permanent crown.',
            ],
        
        ];

     
    }
}
