<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FolderIsDeleting
{
    /**
     * @var Media
     */
    public $folder;

    public function __construct(Media $folder)
    {
        $this->folder = $folder;
    }
}
