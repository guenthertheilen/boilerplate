<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = factory(Role::class)->create(['name' => 'admin']);
        $adminRole->attachPermission('/');
        $adminRole->attachPermission('/foo');

        $userRole = factory(Role::class)->create(['name' => 'user']);
        $userRole->attachPermission('/');
    }
}
