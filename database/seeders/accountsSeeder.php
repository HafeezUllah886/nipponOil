<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Walk-in Customer',
                'type' => 'customer',
                'accountNumber' => '00',
                'warehouseID' => 1,
                'createdBy' => "System",
            ],
            [
                'name' => 'Cash',
                'type' => 'business',
                'category' => 'cash',
                'accountNumber' => '01',
                'warehouseID' => 1,
                'createdBy' => "System",
            ],
            [
                'name' => 'ABC Bank',
                'type' => 'business',
                'category' => 'bank',
                'accountNumber' => '02',
                'warehouseID' => 1,
                'createdBy' => "System",
            ],
            [
                'name' => 'ABC Customer',
                'type' => 'customer',
                'accountNumber' => '04',
                'warehouseID' => 1,
                'createdBy' => "System",
            ],
            [
                'name' => 'ABC Supplier',
                'type' => 'supplier',
                'accountNumber' => '03',
                'warehouseID' => 1,
                'createdBy' => "System",
            ],

        ];

        foreach ($accounts as $key => $account){
            Account::create($account);
        }
    }
}
