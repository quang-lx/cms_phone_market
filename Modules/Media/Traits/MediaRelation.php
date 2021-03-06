<?php

namespace Modules\Media\Traits;

use Modules\Media\Entities\Media;

trait MediaRelation
{
    /**
     * Make the Many To Many Morph To Relation
     * @return object
     */
    public function files()
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('zone', 'id')->withTimestamps()->orderBy('order');
    }

    /**
     * Make the Many to Many Morph to Relation with specific zone
     * @param string $zone
     * @return object
     */
    public function filesByZone($zone)
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('zone', 'id')
            ->wherePivot('zone', '=', $zone)
            ->withTimestamps()
            ->orderBy('order');
    }
}
