<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FileWasUploaded
{
    /**
     * @var Media
     */
    public $file;

    /**
     * @param Media $file
     */
    public function __construct(Media $file)
    {
        $this->file = $file;
    }
}
