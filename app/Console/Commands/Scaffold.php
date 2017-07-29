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
        $this->permission->create(['name' => 'home']);
    }

    private function createAdminRole()
    {
        $this->role->create(['name' => 'admin']);
    }

    private function createAdminUser()
    {
        $this->user->create([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD'))
        ])->attachRole('admin');
    }

    private function attachAdminPermissions()
    {
        $this->role->where('name', '=', 'admin')->first()
            ->attachPermission('home'); // Temporary. This is covered with user role.
    }

    private function attachUserPermissions()
    {
        $this->role->where('name', '=', 'user')->first()
            ->attachPermission('home');
    }
}
