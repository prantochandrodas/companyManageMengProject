<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'add_posts',
            'edit_posts',
            'delete_posts',
            'fund-category',
            'fund-addjustment',
            'expense-category',
            'expense-head',
            'expense',
            'expense-view',
            'expense-pdf',
            'expense-print',
            'expense-report',
            'income-category',
            'income-head',
            'income',
            'income-import-excel',
            'income-pdf',
            'income-print',
            'income-view',
            'income-report',
            'income-report-filter',
            'income-excel-download',
            'manage-user',
            'add-admin',
            'edit-admin',
            'delete-admin',
            'add-superadmin',
            'edit-superadmin',
            'delete-superadmin',
            'add-fund',
            'add-fundAdjustment',
            'add-expenseCategory',
            'add-expenseHead',
            'add-incomeCategory',
            'add-incomeHead',
            'user',
            'add-user',
            'edit-user',
            'delete-user',
            'role',
            'add-role',
            'edit-role',
            'delete-role',
            'permission',
            'add-permission',
            'edit-permission',
            'delete-permission',
            'assign-permission-to-role',
            'assign-permission-super-admin',
            'assign-permission-admin',
            'ledger',
            'ledger-view',
            'ledger-pdf',
            'ledger-print',
            'ledger-excel',

            // Add more permissions as needed
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
