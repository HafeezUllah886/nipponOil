<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class unitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => '1 in box',
                'value' => '1',
                'createdBy' => "System",
            ],
            [
                'name' => '2 in box',
                'value' => '2',
                'createdBy' => "System",
            ],
            [
                'name' => '10 in box',
                'value' => '10',
                'createdBy' => "System",
            ]

        ];

        foreach ($units as $key => $unit){
            Unit::create($unit);
        }
    }
}
