<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FolderStartedMoving
{
    /**
     * @var Media
     */
    public $folder;
    /**
     * @var array
     */
    public $previousData;

    public function __construct(Media $folder, array $previousData)
    {
        $this->folder = $folder;
        $this->previousData = $previousData;
    }
}
