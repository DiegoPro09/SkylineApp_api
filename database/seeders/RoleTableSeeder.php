<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role;
        $role->name = "ROLE_SUPERADMIN";
        $role->save();

        $role2 = new Role;
        $role2->name = "ROLE_ADMIN";
        $role2->save();

        $role3 = new Role;
        $role3->name = "ROLE_PRECEPTOR";
        $role3->save();

        $role4 = new Role;
        $role4->name = "ROLE_TEACHER";
        $role4->save();

        $role5 = new Role;
        $role5->name = "ROLE_USER";
        $role5->save();
    }
}
