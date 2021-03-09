<?php

namespace Modules\Media\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\Media\Repositories\DeletingMedia;

class RemovePolymorphicLink
{
    public function handle($event = null)
    {
        if ($event instanceof DeletingMedia) {
            DB::table('mediables')->where('mediable_id', $event->getEntityId())
                ->where('mediable_type', $event->getClassName())->delete();
        }
    }
}
