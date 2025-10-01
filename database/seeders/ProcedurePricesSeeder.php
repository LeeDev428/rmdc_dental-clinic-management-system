<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcedurePricesSeeder extends Seeder
{
    public function run()
    {
        DB::table('procedure_prices')->insert([
            ['procedure_name' => 'Teeth Cleaning', 'price' => 500.00, 'duration' => '30 minutes'],
            ['procedure_name' => 'Root Canal', 'price' => 1500.00, 'duration' => '1 hour'],
            ['procedure_name' => 'Teeth Whitening', 'price' => 2000.00, 'duration' => '45 minutes'],
        ]);
    }
}
