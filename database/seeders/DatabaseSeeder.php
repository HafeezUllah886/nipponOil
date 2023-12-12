<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ExpenseCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(roleSeeder::class);
        $this->call(permissionSeeder::class);
        $this->call([
            warehousesSeeder::class,
            UserSeeder::class,
            PurchaseStatusSeeder::class,
            brandsSeeder::class,
            unitsSeeder::class,
            categorySeeder::class,
            accountsSeeder::class,
        ]);
        Product::factory(700)->create();
        ExpenseCategory::create(
            [
                'name' => "Products Reconditioning",
                'createdBy' => "System",
            ]
        );
    }
}
