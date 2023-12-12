<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class brandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Brand 1',
                'isActive' => '0',
                'createdBy' => "System",
            ],
            [
                'name' => 'Brand 2',
                'isActive' => '0',
                'createdBy' => "System",
            ],
            
        ];

        foreach ($brands as $key => $brand){
            Brand::create($brand);
        }
    }
}
