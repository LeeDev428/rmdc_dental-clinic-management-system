<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcedurePricesSeeder extends Seeder
{
    public function run()
    {
        $procedures = [
            // General Dentistry
            ['procedure_name' => 'Oral Examination', 'price' => 300.00, 'duration' => '20 minutes'],
            ['procedure_name' => 'Dental Cleaning (Prophylaxis)', 'price' => 500.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Fluoride Treatment', 'price' => 400.00, 'duration' => '15 minutes'],
            ['procedure_name' => 'Dental X-Ray (Single)', 'price' => 250.00, 'duration' => '10 minutes'],
            ['procedure_name' => 'Panoramic X-Ray', 'price' => 800.00, 'duration' => '15 minutes'],
            
            // Restorative Dentistry
            ['procedure_name' => 'Tooth Filling (Amalgam)', 'price' => 800.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Tooth Filling (Composite)', 'price' => 1200.00, 'duration' => '45 minutes'],
            ['procedure_name' => 'Dental Crown (Metal)', 'price' => 3500.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Dental Crown (Porcelain)', 'price' => 6000.00, 'duration' => '1.5 hours'],
            ['procedure_name' => 'Dental Bridge', 'price' => 8000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Inlay/Onlay', 'price' => 4500.00, 'duration' => '1 hour'],
            
            // Endodontics
            ['procedure_name' => 'Root Canal Treatment (Anterior)', 'price' => 3000.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Root Canal Treatment (Premolar)', 'price' => 4500.00, 'duration' => '1.5 hours'],
            ['procedure_name' => 'Root Canal Treatment (Molar)', 'price' => 6000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Apicoectomy', 'price' => 8000.00, 'duration' => '1.5 hours'],
            
            // Oral Surgery
            ['procedure_name' => 'Simple Tooth Extraction', 'price' => 1000.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Surgical Tooth Extraction', 'price' => 2500.00, 'duration' => '45 minutes'],
            ['procedure_name' => 'Wisdom Tooth Extraction', 'price' => 3500.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Impacted Tooth Removal', 'price' => 5000.00, 'duration' => '1.5 hours'],
            
            // Prosthodontics
            ['procedure_name' => 'Complete Dentures (Upper/Lower)', 'price' => 15000.00, 'duration' => '3 hours'],
            ['procedure_name' => 'Partial Dentures', 'price' => 8000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Denture Repair', 'price' => 1500.00, 'duration' => '45 minutes'],
            ['procedure_name' => 'Denture Reline', 'price' => 2000.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Dental Implant', 'price' => 35000.00, 'duration' => '2 hours'],
            
            // Orthodontics
            ['procedure_name' => 'Orthodontic Consultation', 'price' => 500.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Metal Braces (Full)', 'price' => 40000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Ceramic Braces (Full)', 'price' => 60000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Braces Adjustment', 'price' => 800.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Retainers', 'price' => 3000.00, 'duration' => '45 minutes'],
            
            // Cosmetic Dentistry
            ['procedure_name' => 'Teeth Whitening (In-Office)', 'price' => 8000.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Teeth Whitening (Take-Home Kit)', 'price' => 5000.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Dental Veneers (Per Tooth)', 'price' => 12000.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Tooth Bonding', 'price' => 2500.00, 'duration' => '45 minutes'],
            
            // Periodontics
            ['procedure_name' => 'Scaling and Root Planing', 'price' => 3000.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Gum Grafting', 'price' => 15000.00, 'duration' => '2 hours'],
            ['procedure_name' => 'Periodontal Maintenance', 'price' => 1500.00, 'duration' => '45 minutes'],
            
            // Pediatric Dentistry
            ['procedure_name' => 'Pediatric Check-up', 'price' => 400.00, 'duration' => '20 minutes'],
            ['procedure_name' => 'Dental Sealants', 'price' => 600.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Fluoride Varnish (Kids)', 'price' => 350.00, 'duration' => '15 minutes'],
            
            // Emergency Services
            ['procedure_name' => 'Emergency Consultation', 'price' => 800.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Emergency Pain Relief', 'price' => 1000.00, 'duration' => '30 minutes'],
        ];

        foreach ($procedures as $procedure) {
            DB::table('procedure_prices')->insert([
                'procedure_name' => $procedure['procedure_name'],
                'price' => $procedure['price'],
                'duration' => $procedure['duration'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
