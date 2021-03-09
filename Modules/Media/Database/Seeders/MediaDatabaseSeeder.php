<?php

namespace Modules\Media\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Permission;

class MediaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        collect([
            ['name' => 'list media', 'en' => ['title' => 'List all of media'], 'vi' => ['title' => 'Xem danh sách media']],
            ['name' => 'create media', 'en' => ['title' => 'Upload a media'], 'vi' => ['title' => 'Upload media']],
            ['name' => 'edit media', 'en' => ['title' => 'Edit a media'], 'vi' => ['title' => 'Cập nhật media']],
            ['name' => 'delete media', 'en' => ['title' => 'Delete a media'], 'vi' => ['title' => 'Xóa media']],
        ])->map(function ($permission) {
            return Permission::create($permission);
        });
    }
}
