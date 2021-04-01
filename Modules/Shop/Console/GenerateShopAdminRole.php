<?php

namespace Modules\Shop\Console;

use Illuminate\Console\Command;
use Modules\Mon\Entities\User;
use Modules\Mon\Repositories\PermissionRepository;
use Spatie\Permission\Models\Role;


class GenerateShopAdminRole extends Command
{
    /**
     * The console command name.
     * php artisan admin:create-root-user email password
     * @var string
     */
    protected $name = 'shop:gerenate-shop-admin-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate shop admin role.';

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
        $role = Role::findOrCreate(User::ROLE_SHOP_ADMIN  );
        $this->assignAllRoleToSuper($role);

    }
    public function assignAllRoleToSuper($role) {
        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = app(PermissionRepository::class);
        $permissions = $permissionRepository->newQueryBuilder()->where('module', 'shop')->get();
        $role->syncPermissions($permissions);
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [

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

    protected function startWith($str, $prefix)
    {
        if (strpos($str, $prefix) === 0) {
            return true;
        }
        return false;
    }
}
