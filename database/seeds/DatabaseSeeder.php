<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mail::fake();

        Artisan::call('scaffold:build');
        $this->activateAdmin();

        $this->call(UsersTableSeeder::class);
    }

    private function activateAdmin()
    {
        $admin = User::whereName(config('scaffold.admin_name'))->first();
        $admin->activate();
        $admin->update([
            'password' => bcrypt('admin')
        ]);
    }
}
