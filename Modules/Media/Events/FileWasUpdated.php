<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FileWasUpdated
{
    /**
     * @var Media
     */
    public $file;

    public function __construct(Media $file)
    {
        $this->file = $file;
    }
}
