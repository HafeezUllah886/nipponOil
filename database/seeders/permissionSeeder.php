<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'View Users']);
        Permission::create(['name' => 'Add User']);
        Permission::create(['name' => 'Edit User']);
        Permission::create(['name' => 'Delete User']);
        Permission::create(['name' => 'View Owner Account']);
        Permission::create(['name' => 'View Admin Account']);
        Permission::create(['name' => 'View Permissions']);
        Permission::create(['name' => 'View User Permissions']);
        Permission::create(['name' => 'Assign Permissions To User']);
        Permission::create(['name' => 'Add Role']);
        Permission::create(['name' => 'View Roles']);
        Permission::create(['name' => 'Assign Role To User']);
        Permission::create(['name' => 'View Reports']);
        Permission::create(['name' => 'View Purchases']);
        Permission::create(['name' => 'Create Purchase']);
        Permission::create(['name' => 'Edit Purchase']);
        Permission::create(['name' => 'Delete Purchase']);
        Permission::create(['name' => 'Pay Purchase Payments']);
        Permission::create(['name' => 'Receive Purchase Products']);
        Permission::create(['name' => 'View Sales']);
        Permission::create(['name' => 'Create Sale']);
        Permission::create(['name' => 'Receive Sale Payments']);
        Permission::create(['name' => 'Edit Sale']);
        Permission::create(['name' => 'Delete Sale']);
        Permission::create(['name' => 'View Warehouses']);
        Permission::create(['name' => 'Add Warehouse']);
        Permission::create(['name' => 'Edit Warehouse']);
        Permission::create(['name' => 'Delete Warehouse']);
        Permission::create(['name' => 'View Units']);
        Permission::create(['name' => 'Add Unit']);
        Permission::create(['name' => 'Edit Unit']);
        Permission::create(['name' => 'Delete Unit']);
        Permission::create(['name' => 'View Products']);
        Permission::create(['name' => 'Add Products']);
        Permission::create(['name' => 'Edit Product']);
        Permission::create(['name' => 'Delete Product']);
        Permission::create(['name' => 'View Brands']);
        Permission::create(['name' => 'Edit Brand']);
        Permission::create(['name' => 'Delete Brand']);
        Permission::create(['name' => 'View Categories']);
        Permission::create(['name' => 'Edit Category']);
        Permission::create(['name' => 'Delete Category']);
        Permission::create(['name' => 'Create Accounts']);
        Permission::create(['name' => 'View Accounts']);
        Permission::create(['name' => 'Edit Account']);
        Permission::create(['name' => 'Delete Account']);
        Permission::create(['name' => 'View Deposit/Withdrawals']);
        Permission::create(['name' => 'Create Deposit/Withdrawals']);
        Permission::create(['name' => 'Delete Deposit/Withdrawals']);
        Permission::create(['name' => 'View Transfers']);
        Permission::create(['name' => 'Create Transfers']);
        Permission::create(['name' => 'Delete Transfers']);
        Permission::create(['name' => 'View Accounts Statement']);
        Permission::create(['name' => 'View Transfer']);
        Permission::create(['name' => 'Delete Transfer']);
        Permission::create(['name' => 'View Expenses']);
        Permission::create(['name' => 'Create Expense']);
        Permission::create(['name' => 'Delete Expense']);
        Permission::create(['name' => 'All Warehouses']);
        Permission::create(['name' => 'View Attendance']);
        Permission::create(['name' => 'Create Attendance']);
        Permission::create(['name' => 'Delete Attendance']);
        Permission::create(['name' => 'Edit Attendance']);
        Permission::create(['name' => 'View Payrolls']);
        Permission::create(['name' => 'Generate Payrolls']);
        Permission::create(['name' => 'Pay Salary']);
        Permission::create(['name' => 'Delete Payroll']);
        Permission::create(['name' => 'Create Employee Advances']);
        Permission::create(['name' => 'Edit Employee Advances']);
        Permission::create(['name' => 'Delete Employee Advances']);
        Permission::create(['name' => 'Adjust Stocks']);
        

        $role = Role::findByName('Owner');
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
    }
}
