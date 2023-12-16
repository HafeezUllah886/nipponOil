<?php

namespace Database\Seeders;

use App\Models\employees;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
                'name' => 'Owner',
                'email' => 'owner@email.com',
                'password' => Hash::make('owner'),
            'warehouseID' => 1,
            'createdBy' => "System",
        ])->assignRole('Owner');

        employees::create([
            'name' => 'Owner',
            'designation' => 'Owner',
            'email' => 'owner@email.com',
            'phone' => '03243243324',
            'status' => 'Active',
            'address' => 'Abc Quetta',
            'salary' => '100000',
            'doe' => now(),
            'image' => null,
            'warehouseID' => 1,
            'createdBy' => "System",
        ]);

        User::create([
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin'),
            'warehouseID' => 1,
            'createdBy' => "System",
        ])->assignRole('Admin');

        employees::create([
            'name' => 'Admin',
            'designation' => 'Admin',
            'email' => 'admin@email.com',
            'phone' => '03243243324',
            'status' => 'Active',
            'address' => 'Abc Quetta',
            'salary' => '50000',
            'doe' => now(),
            'image' => null,
            'warehouseID' => 1,
            'createdBy' => "System",
        ]);

        User::create([
            'name' => 'Cashier',
            'email' => 'cashier@email.com',
            'password' => Hash::make('cashier'),
            'warehouseID' => 2,
            'createdBy' => "System",
        ])->assignRole('Cashier');

        employees::create([
            'name' => 'Cashier',
            'designation' => 'Cashier',
            'email' => 'cashier@email.com',
            'phone' => '03243243324',
            'status' => 'Active',
            'address' => 'Abc Quetta',
            'salary' => '20000',
            'doe' => now(),
            'image' => null,
            'warehouseID' => 1,
            'createdBy' => "System",
        ]);
    }
}
