<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Agent
            |--------------------------------------------------------------------------
            */
            ['group' => 'Agent', 'name' => 'Agent Show'],
            ['group' => 'Agent', 'name' => 'Agent Create'],
            ['group' => 'Agent', 'name' => 'Agent Edit'],
            ['group' => 'Agent', 'name' => 'Agent Delete'],

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */
            ['group' => 'Employee', 'name' => 'Employee Show'],
            ['group' => 'Employee', 'name' => 'Employee Create'],
            ['group' => 'Employee', 'name' => 'Employee Edit'],
            ['group' => 'Employee', 'name' => 'Employee Delete'],

            /*
            |--------------------------------------------------------------------------
            | Game Entry
            |--------------------------------------------------------------------------
            */
            ['group' => 'Game Entry', 'name' => 'Game Entry Show'],
            ['group' => 'Game Entry', 'name' => 'Game Entry Create'],

            /*
            |--------------------------------------------------------------------------
            | Role & Permission
            |--------------------------------------------------------------------------
            */
            ['group' => 'Role & Permission', 'name' => 'Role Show'],
            ['group' => 'Role & Permission', 'name' => 'Role Create'],
            ['group' => 'Role & Permission', 'name' => 'Role Edit'],
            ['group' => 'Role & Permission', 'name' => 'Role Delete'],
            ['group' => 'Role & Permission', 'name' => 'Permission Show'],
            ['group' => 'Role & Permission', 'name' => 'Permission Create'],
            ['group' => 'Role & Permission', 'name' => 'Permission Edit'],
            ['group' => 'Role & Permission', 'name' => 'Permission Delete'],

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            ['group' => 'Report', 'name' => 'Single Report'],
            ['group' => 'Report', 'name' => 'Patti Report'],
            ['group' => 'Report', 'name' => 'Entry Report'],

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */
            ['group' => 'Result', 'name' => 'Result Check'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ],
                [
                    'group' => $permission['group'],
                ]
            );
        }
    }
}