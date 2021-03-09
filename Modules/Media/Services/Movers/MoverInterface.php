<?php

namespace Modules\Media\Services\Movers;

use Modules\Media\Entities\Media;

interface MoverInterface
{
    public function move(Media $file, Media $destination) : bool;
}
