<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;


class GenerateAdminPermission extends Command
{
    /**
     * The console command name.
     * php artisan admin:create-root-user email password
     * @var string
     */
    protected $name = 'admin:gerenate-permission-route';

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
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $route) {
            $routeName = $route->getName();
            if ($this->startWith($routeName, 'admin')) {
                $permission = Permission::findOrCreate($routeName );
				$tokens = explode('.', $routeName);
				if (isset($tokens[1])) {
					$permission->group = $tokens[1];
				}
				if (isset($tokens[2])) {
					$permission->title = $tokens[2];
				}
				$permission->save();
            }

        }


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
    protected function startWith($str,$prefix) {
        if (strpos($str, $prefix) === 0) {
            return true;
        }
        return false;
    }
}
