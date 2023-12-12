<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseStatuses = [
            ['id' => 1, 'name'=>'Received'],
            ['id' => 2, 'name'=>'Partial'],
            ['id' => 3, 'name'=>'Pending'],
            ['id' => 4, 'name'=>'Ordered'],

        ];

        foreach ($purchaseStatuses as $purchaseStatus){
            DB::table('purchaseStatuses')->insert(['purchaseStatusID' => $purchaseStatus['id'], 'name' => $purchaseStatus['name']]);
        }
    }
}
