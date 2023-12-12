<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = [
            [
                'name' => 'Category 1',
                'image' => 'Screenshot 2023-02-09 142113.png',
                'isActive' => '0',
                'createdBy' => "System",
            ],
            [
                'name' => 'Category 2',
                'image' => 'Screenshot 2023-02-09 142113.png',
                'isActive' => '0',
                'createdBy' => "System",
            ],
            
        ];

        foreach ($cats as $key => $cat){
            Category::create($cat);
        }
    }
}
