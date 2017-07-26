<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin')
        ]);
        $admin->attachRole('admin');

        $user = factory(User::class)->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('user')
        ]);
        $user->attachRole('user');
    }
}
