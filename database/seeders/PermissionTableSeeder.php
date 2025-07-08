<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        // Permission::truncate();

        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'role-view',
            'role-all',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'user-view',
            'user-all',
  
            'userprofile-edit',
             
            'dashboard-list',
            'dashboard-create',
            'dashboard-edit',
            'dashboard-view',
            'dashboard-delete',
            'dashboard-all',


         ];
       
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}



