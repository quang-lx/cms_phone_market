<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FileStartedMoving
{
    /**
     * @var Media
     */
    public $file;
    /**
     * @var array
     */
    public $previousData;

    public function __construct(Media $file, array $previousData)
    {
        $this->file = $file;
        $this->previousData = $previousData;
    }
}
