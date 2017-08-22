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
     * @var User
     */
    private $user;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var Permission
     */
    private $permission;

    /**
     * Create a new command instance.
     *
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }

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
            $this->permission->create(['name' => $permission]);
        }
    }

    private function createAdminRole()
    {
        $this->role->create(['name' => 'admin']);
    }

    private function createAdminUser()
    {
        $this->user->create([
            'name' => config('scaffold.admin_name'),
            'email' => config('scaffold.admin_email'),
            'password' => bcrypt(config('scaffold.admin_password')),
            'active' => 1
        ])->attachRole('admin');
    }

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

        $admin = $this->role->where('name', '=', 'admin')->first();

        foreach ($adminPermissions as $adminPermission) {
            $admin->attachPermission($adminPermission);
        }
    }

    private function attachUserPermissions()
    {
        $this->role->where('name', '=', 'user')->first()
            ->attachPermission('home');
    }
}
