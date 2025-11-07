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
            [
                'procedure_name' => 'Oral Examination', 
                'price' => 300.00, 
                'duration' => '20 minutes',
                'image_path' => 'procedures/oral-examination.jpg',
                'description' => 'Comprehensive oral health assessment and examination by our experienced dentist.'
            ],
            [
                'procedure_name' => 'Dental Cleaning (Prophylaxis)', 
                'price' => 500.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/dental-cleaning.jpg',
                'description' => 'Professional teeth cleaning to remove plaque and tartar buildup.'
            ],
            [
                'procedure_name' => 'Fluoride Treatment', 
                'price' => 400.00, 
                'duration' => '15 minutes',
                'image_path' => 'procedures/fluoride-treatment.jpg',
                'description' => 'Strengthens tooth enamel and prevents tooth decay.'
            ],
            [
                'procedure_name' => 'Dental X-Ray (Single)', 
                'price' => 250.00, 
                'duration' => '10 minutes',
                'image_path' => 'procedures/dental-xray.jpg',
                'description' => 'Single tooth X-ray for detailed diagnosis.'
            ],
            [
                'procedure_name' => 'Panoramic X-Ray', 
                'price' => 800.00, 
                'duration' => '15 minutes',
                'image_path' => 'procedures/panoramic-xray.jpg',
                'description' => 'Full mouth X-ray showing all teeth and jaw structure.'
            ],
            
            // Restorative Dentistry
            [
                'procedure_name' => 'Tooth Filling (Amalgam)', 
                'price' => 800.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/amalgam-filling.jpg',
                'description' => 'Durable silver-colored filling for cavities.'
            ],
            [
                'procedure_name' => 'Tooth Filling (Composite)', 
                'price' => 1200.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/composite-filling.jpg',
                'description' => 'Tooth-colored filling that blends naturally with your teeth.'
            ],
            [
                'procedure_name' => 'Dental Crown (Metal)', 
                'price' => 3500.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/metal-crown.jpg',
                'description' => 'Strong and durable metal crown for damaged teeth.'
            ],
            [
                'procedure_name' => 'Dental Crown (Porcelain)', 
                'price' => 6000.00, 
                'duration' => '1.5 hours',
                'image_path' => 'procedures/porcelain-crown.jpg',
                'description' => 'Natural-looking porcelain crown for front or back teeth.'
            ],
            [
                'procedure_name' => 'Dental Bridge', 
                'price' => 8000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/dental-bridge.jpg',
                'description' => 'Replace missing teeth with a custom bridge.'
            ],
            [
                'procedure_name' => 'Inlay/Onlay', 
                'price' => 4500.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/inlay-onlay.jpg',
                'description' => 'Custom-fitted restoration for moderate tooth damage.'
            ],
            
            // Endodontics
            [
                'procedure_name' => 'Root Canal Treatment (Anterior)', 
                'price' => 3000.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/root-canal.jpg',
                'description' => 'Root canal treatment for front teeth to save infected tooth.'
            ],
            [
                'procedure_name' => 'Root Canal Treatment (Premolar)', 
                'price' => 4500.00, 
                'duration' => '1.5 hours',
                'image_path' => 'procedures/root-canal-premolar.jpg',
                'description' => 'Root canal treatment for premolar teeth.'
            ],
            [
                'procedure_name' => 'Root Canal Treatment (Molar)', 
                'price' => 6000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/root-canal-molar.jpg',
                'description' => 'Complex root canal treatment for molar teeth.'
            ],
            [
                'procedure_name' => 'Apicoectomy', 
                'price' => 8000.00, 
                'duration' => '1.5 hours',
                'image_path' => 'procedures/apicoectomy.jpg',
                'description' => 'Surgical removal of tooth root tip and surrounding tissue.'
            ],
            
            // Oral Surgery
            [
                'procedure_name' => 'Simple Tooth Extraction', 
                'price' => 1000.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/tooth-extraction.jpg',
                'description' => 'Quick and painless removal of a visible tooth.'
            ],
            [
                'procedure_name' => 'Surgical Tooth Extraction', 
                'price' => 2500.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/surgical-extraction.jpg',
                'description' => 'Surgical removal of a tooth that requires incision.'
            ],
            [
                'procedure_name' => 'Wisdom Tooth Extraction', 
                'price' => 3500.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/wisdom-tooth.jpg',
                'description' => 'Removal of problematic wisdom teeth.'
            ],
            [
                'procedure_name' => 'Impacted Tooth Removal', 
                'price' => 5000.00, 
                'duration' => '1.5 hours',
                'image_path' => 'procedures/impacted-tooth.jpg',
                'description' => 'Surgical removal of impacted or embedded teeth.'
            ],
            
            // Prosthodontics
            [
                'procedure_name' => 'Complete Dentures (Upper/Lower)', 
                'price' => 15000.00, 
                'duration' => '3 hours',
                'image_path' => 'procedures/complete-dentures.jpg',
                'description' => 'Full set of removable dentures for upper and lower jaws.'
            ],
            [
                'procedure_name' => 'Partial Dentures', 
                'price' => 8000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/partial-dentures.jpg',
                'description' => 'Custom partial dentures to replace missing teeth.'
            ],
            [
                'procedure_name' => 'Denture Repair', 
                'price' => 1500.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/denture-repair.jpg',
                'description' => 'Professional repair service for broken dentures.'
            ],
            [
                'procedure_name' => 'Denture Reline', 
                'price' => 2000.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/denture-reline.jpg',
                'description' => 'Relining service to improve denture fit and comfort.'
            ],
            [
                'procedure_name' => 'Dental Implant', 
                'price' => 35000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/dental-implant.jpg',
                'description' => 'Permanent tooth replacement with titanium implant.'
            ],
            
            // Orthodontics
            [
                'procedure_name' => 'Orthodontic Consultation', 
                'price' => 500.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/orthodontic-consult.jpg',
                'description' => 'Initial consultation for braces and orthodontic treatment.'
            ],
            [
                'procedure_name' => 'Metal Braces (Full)', 
                'price' => 40000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/metal-braces.jpg',
                'description' => 'Traditional metal braces for effective teeth alignment.'
            ],
            [
                'procedure_name' => 'Ceramic Braces (Full)', 
                'price' => 60000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/ceramic-braces.jpg',
                'description' => 'Aesthetic tooth-colored braces for a subtle look.'
            ],
            [
                'procedure_name' => 'Braces Adjustment', 
                'price' => 800.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/braces-adjustment.jpg',
                'description' => 'Regular braces adjustment and wire tightening.'
            ],
            [
                'procedure_name' => 'Retainers', 
                'price' => 3000.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/retainers.jpg',
                'description' => 'Custom retainers to maintain teeth alignment after braces.'
            ],
            
            // Cosmetic Dentistry
            [
                'procedure_name' => 'Teeth Whitening (In-Office)', 
                'price' => 8000.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/teeth-whitening.jpg',
                'description' => 'Professional in-office teeth whitening for immediate results.'
            ],
            [
                'procedure_name' => 'Teeth Whitening (Take-Home Kit)', 
                'price' => 5000.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/whitening-kit.jpg',
                'description' => 'Custom take-home whitening kit for gradual brightening.'
            ],
            [
                'procedure_name' => 'Dental Veneers (Per Tooth)', 
                'price' => 12000.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/veneers.jpg',
                'description' => 'Thin porcelain shells to perfect your smile.'
            ],
            [
                'procedure_name' => 'Tooth Bonding', 
                'price' => 2500.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/tooth-bonding.jpg',
                'description' => 'Composite resin to repair chips and improve appearance.'
            ],
            
            // Periodontics
            [
                'procedure_name' => 'Scaling and Root Planing', 
                'price' => 3000.00, 
                'duration' => '1 hour',
                'image_path' => 'procedures/scaling-root-planing.jpg',
                'description' => 'Deep cleaning to treat gum disease and restore health.'
            ],
            [
                'procedure_name' => 'Gum Grafting', 
                'price' => 15000.00, 
                'duration' => '2 hours',
                'image_path' => 'procedures/gum-grafting.jpg',
                'description' => 'Surgical procedure to repair receding gums.'
            ],
            [
                'procedure_name' => 'Periodontal Maintenance', 
                'price' => 1500.00, 
                'duration' => '45 minutes',
                'image_path' => 'procedures/periodontal-maintenance.jpg',
                'description' => 'Regular maintenance cleaning for gum disease prevention.'
            ],
            
            // Pediatric Dentistry
            [
                'procedure_name' => 'Pediatric Check-up', 
                'price' => 400.00, 
                'duration' => '20 minutes',
                'image_path' => 'procedures/pediatric-checkup.jpg',
                'description' => 'Gentle dental examination for children.'
            ],
            [
                'procedure_name' => 'Dental Sealants', 
                'price' => 600.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/dental-sealants.jpg',
                'description' => 'Protective coating to prevent cavities in children.'
            ],
            [
                'procedure_name' => 'Fluoride Varnish (Kids)', 
                'price' => 350.00, 
                'duration' => '15 minutes',
                'image_path' => 'procedures/fluoride-kids.jpg',
                'description' => 'Fluoride treatment to strengthen children\'s teeth.'
            ],
            
            // Emergency Services
            [
                'procedure_name' => 'Emergency Consultation', 
                'price' => 800.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/emergency-consultation.jpg',
                'description' => 'Immediate consultation for dental emergencies.'
            ],
            [
                'procedure_name' => 'Emergency Pain Relief', 
                'price' => 1000.00, 
                'duration' => '30 minutes',
                'image_path' => 'procedures/emergency-pain-relief.jpg',
                'description' => 'Quick pain relief treatment for dental emergencies.'
            ],
        ];

        foreach ($procedures as $procedure) {
            DB::table('procedure_prices')->insert([
                'procedure_name' => $procedure['procedure_name'],
                'price' => $procedure['price'],
                'duration' => $procedure['duration'],
                'image_path' => $procedure['image_path'] ?? null,
                'description' => $procedure['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
