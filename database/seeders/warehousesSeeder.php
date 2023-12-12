<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class warehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'createdBy' => "System",
            ],
            [
                'name' => 'Warehouse 2',
                'createdBy' => "System",
            ],

        ];

        foreach ($warehouses as $key => $warehouse){
            Warehouse::create($warehouse);
        }
    }
}
