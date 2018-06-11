<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'Employee';
        $role_employee->save();
        $role_admin = new Role();
        $role_admin->name= 'Administrator';
        $role_admin->save();
        }
}
