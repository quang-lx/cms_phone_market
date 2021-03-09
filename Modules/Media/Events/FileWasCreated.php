<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FileWasCreated
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
