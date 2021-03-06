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
        User::create([
            'name' => config('scaffold.admin_name'),
            'email' => config('scaffold.admin_email'),
            'password' => ''
        ])->attachRole('admin');
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

        $admin = Role::where('name', 'admin')->first();

        foreach ($adminPermissions as $adminPermission) {
            $admin->attachPermission($adminPermission);
        }
    }

    /**
     * Attach default permission to user role.
     */
    private function attachUserPermissions()
    {
        Role::where('name', 'user')->first()
            ->attachPermission('home');
    }
}
