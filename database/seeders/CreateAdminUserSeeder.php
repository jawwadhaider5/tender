<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\BusinessDetail;
use App\Models\CompanyAccount;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\ExpenseCategory;
use App\Models\ItemCategory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin user
        $user = User::create([
            'name' => 'Super Admin', 
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678')
        ]);
 
        $userdetail = UserDetail::create([
            'user_id' => $user->id,
            'business_detail_id' => 1,
            'image' => 'open/images/userdetail-images/admin.png',
            'gender' => 'male',
            'date_of_birth' => '1990-10-05',
            'cnic_number' => 'ASD4523689',
            'passport_number' => '45239',
            'phone_no_one' => '009711236987',
            'phone_no_two' => '009715236784',
            'address_one' => '6th Road Main Market',
            'address_two' => '6th Road Main Market',
            'account_type' => 'Company',
            'joining_date' => '2017-01-02',
            'leaving_date' => '',
            'salary_per_month' => '5000',
        ]);

  
        
        $role = Role::create(['name' => 'Super Admin']); 
       
        $permissions = Permission::pluck('id','id')->all();
     
        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
