<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryItems = [
            // Dental Instruments
            [
                'name' => 'Dental Mirror',
                'price' => 250.00,
                'quantity' => 50,
                'low_stock_threshold' => 10,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'MedSupply Inc.',
                'category' => 'Dental Instruments',
            ],
            [
                'name' => 'Dental Explorer',
                'price' => 300.00,
                'quantity' => 35,
                'low_stock_threshold' => 8,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'DentPro Supplies',
                'category' => 'Dental Instruments',
            ],
            [
                'name' => 'Extraction Forceps',
                'price' => 1500.00,
                'quantity' => 15,
                'low_stock_threshold' => 5,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'Surgical Tools Co.',
                'category' => 'Surgical Tools',
            ],
            [
                'name' => 'Scaler',
                'price' => 450.00,
                'quantity' => 28,
                'low_stock_threshold' => 10,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'DentPro Supplies',
                'category' => 'Dental Instruments',
            ],

            // Patient Care Supplies
            [
                'name' => 'Disposable Gloves',
                'price' => 800.00,
                'quantity' => 25,
                'low_stock_threshold' => 10,
                'unit' => 'boxes',
                'items_per_unit' => 100,
                'expiration_date' => Carbon::now()->addMonths(18)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'SafeGuard Medical',
                'category' => 'Patient Care Supplies',
            ],
            [
                'name' => 'Face Masks',
                'price' => 500.00,
                'quantity' => 8,
                'low_stock_threshold' => 10,
                'unit' => 'boxes',
                'items_per_unit' => 50,
                'expiration_date' => Carbon::now()->addMonths(12)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'SafeGuard Medical',
                'category' => 'Protective Gear',
            ],
            [
                'name' => 'Dental Bibs',
                'price' => 350.00,
                'quantity' => 45,
                'low_stock_threshold' => 15,
                'unit' => 'packs',
                'items_per_unit' => 125,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'CleanCare Supplies',
                'category' => 'Patient Care Supplies',
            ],
            [
                'name' => 'Cotton Rolls',
                'price' => 200.00,
                'quantity' => 60,
                'low_stock_threshold' => 20,
                'unit' => 'packs',
                'items_per_unit' => 100,
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'MedSupply Inc.',
                'category' => 'Patient Care Supplies',
            ],

            // Sterilization Products
            [
                'name' => 'Autoclave Pouches',
                'price' => 600.00,
                'quantity' => 18,
                'low_stock_threshold' => 8,
                'unit' => 'boxes',
                'items_per_unit' => 200,
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'Sterile Systems Ltd.',
                'category' => 'Sterilization Products',
            ],
            [
                'name' => 'Disinfectant Spray',
                'price' => 450.00,
                'quantity' => 12,
                'low_stock_threshold' => 6,
                'unit' => 'bottles',
                'items_per_unit' => 1,
                'expiration_date' => Carbon::now()->addMonths(24)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'CleanCare Supplies',
                'category' => 'Sterilization Products',
            ],
            [
                'name' => 'Sterilization Indicators',
                'price' => 300.00,
                'quantity' => 22,
                'low_stock_threshold' => 10,
                'unit' => 'packs',
                'items_per_unit' => 250,
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'Sterile Systems Ltd.',
                'category' => 'Sterilization Products',
            ],

            // Consumables
            [
                'name' => 'Anesthetic Cartridges',
                'price' => 1200.00,
                'quantity' => 40,
                'low_stock_threshold' => 15,
                'unit' => 'boxes',
                'items_per_unit' => 50,
                'expiration_date' => Carbon::now()->addMonths(18)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'PharmaDent Inc.',
                'category' => 'Patient Care Supplies',
            ],
            [
                'name' => 'Composite Resin',
                'price' => 2500.00,
                'quantity' => 5,
                'low_stock_threshold' => 8,
                'unit' => 'tubes',
                'items_per_unit' => 1,
                'expiration_date' => Carbon::now()->addMonths(15)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'DentalMaterials Co.',
                'category' => 'Patient Care Supplies',
            ],
            [
                'name' => 'Dental Cement',
                'price' => 800.00,
                'quantity' => 20,
                'low_stock_threshold' => 10,
                'unit' => 'bottles',
                'items_per_unit' => 1,
                'expiration_date' => Carbon::now()->addMonths(20)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'DentalMaterials Co.',
                'category' => 'Patient Care Supplies',
            ],

            // Equipment
            [
                'name' => 'LED Curing Light',
                'price' => 8500.00,
                'quantity' => 3,
                'low_stock_threshold' => 1,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'TechDental Equipment',
                'category' => 'Equipment',
            ],
            [
                'name' => 'Ultrasonic Scaler',
                'price' => 15000.00,
                'quantity' => 2,
                'low_stock_threshold' => 1,
                'unit' => 'pieces',
                'items_per_unit' => 1,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'TechDental Equipment',
                'category' => 'Equipment',
            ],

            // Low stock items
            [
                'name' => 'Needle Tips',
                'price' => 150.00,
                'quantity' => 6,
                'low_stock_threshold' => 20,
                'unit' => 'boxes',
                'items_per_unit' => 100,
                'expiration_date' => Carbon::now()->addMonths(24)->format('Y-m-d'),
                'expiration_type' => 'Expirable',
                'supplier' => 'MedSupply Inc.',
                'category' => 'Patient Care Supplies',
            ],
            [
                'name' => 'Suction Tips',
                'price' => 400.00,
                'quantity' => 0,
                'low_stock_threshold' => 10,
                'unit' => 'boxes',
                'items_per_unit' => 100,
                'expiration_date' => null,
                'expiration_type' => 'Inexpirable',
                'supplier' => 'DentPro Supplies',
                'category' => 'Patient Care Supplies',
            ],
        ];

        foreach ($inventoryItems as $item) {
            DB::table('inventories')->insert([
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'low_stock_threshold' => $item['low_stock_threshold'],
                'unit' => $item['unit'],
                'items_per_unit' => $item['items_per_unit'],
                'expiration_date' => $item['expiration_date'],
                'expiration_type' => $item['expiration_type'],
                'supplier' => $item['supplier'],
                'category' => $item['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
