<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Mon\Entities\User;
use Modules\Mon\Repositories\PermissionRepository;
use Modules\Mon\Repositories\UserRepository;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateAdminUser extends Command
{
    /**
     * The console command name.
     * php artisan admin:create-root-user username password
     * @var string
     */
    protected $name = 'admin:create-root-user {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * @var $userRepository UserRepository
         */
        $userRepository = app(UserRepository::class);

        $username = $this->argument('username');
        $password = $this->argument('password');

        $existEmail = $userRepository->findByAttributes(['username'=> $username]);
        if ($existEmail) {
            $this->error("Email exists");
            return false;
        }
        $role = Role::findOrCreate('cms_login'  );
        $superAdminRole = Role::findOrCreate('super_admin'  );
        Artisan::call('admin:gerenate-permission-route', [  ]);
        $this->assignAllRoleToSuper($superAdminRole);
        /** @var User $user */
        $user = $userRepository->create([
            'name' => 'Admin',
            'username' => $username,
//            'email_verified_at' => now(),
            'password' => Hash::make($password), // secret
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole($role);
        $user->assignRole($superAdminRole);

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['username', InputArgument::REQUIRED, 'username'],
            ['password', InputArgument::REQUIRED, 'password'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
    public function assignAllRoleToSuper($role) {
        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = app(PermissionRepository::class);
        $permissions = $permissionRepository->newQueryBuilder()->get();
        $role->syncPermissions($permissions);
    }
}
