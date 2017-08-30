<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class Scaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold authorization system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->createPermissions();
        $this->createAdminRole();
        $this->createAdminUser();
        $this->attachAdminPermissions();
        $this->attachUserPermissions();
    }

    /**
     * Create all available permissions.
     */
    private function createPermissions()
    {
        $permissions = [
            'home',
            'permission.create',
            'permission.destroy',
            'permission.edit',
            'permission.index',
            'permission.show',
            'permission.store',
            'permission.update',
            'role.create',
            'role.destroy',
            'role.edit',
            'role.index',
            'role.show',
            'role.store',
            'role.update',
            'user.create',
            'user.destroy',
            'user.edit',
            'user.index',
            'user.show',
            'user.store',
            'user.update',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    /**
     * Create role named 'admin'.
     */
    private function createAdminRole()
    {
        Role::create(['name' => 'admin']);
    }

    /**
     * Create user with 'admin' role. Name, Email and Password are read from config.
     */
    private function createAdminUser()
    {
        // TODO: Prevent sending of activation mail
        // Maybe we should remove the password from config
        // and let the scaffolded admin run through the normal activation process
        // so that he must activate the account and create his own password.
        User::create([
            'name' => config('scaffold.admin_name'),
            'email' => config('scaffold.admin_email'),
            'password' => bcrypt(config('scaffold.admin_password'))
        ])->attachRole('admin')->activate();
    }

    /**
     * Attach default permissions to admin role.
     */
    private function attachAdminPermissions()
    {
        $adminPermissions = [
            'permission.create',
            'permission.destroy',
            'permission.edit',
            'permission.index',
            'permission.show',
            'permission.store',
            'permission.update',
            'role.create',
            'role.destroy',
            'role.edit',
            'role.index',
            'role.show',
            'role.store',
            'role.update',
            'user.create',
            'user.destroy',
            'user.edit',
            'user.index',
            'user.show',
            'user.store',
            'user.update',
        ];

        $admin = Role::whereName('admin')->first();

        foreach ($adminPermissions as $adminPermission) {
            $admin->attachPermission($adminPermission);
        }
    }

    /**
     * Attach default permission to user role.
     */
    private function attachUserPermissions()
    {
        Role::whereName('user')->first()
            ->attachPermission('home');
    }
}
