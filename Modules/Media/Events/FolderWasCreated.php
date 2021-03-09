<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FolderWasCreated
{
    /**
     * @var Media
     */
    public $folder;
    /**
     * @var array
     */
    public $data;

    public function __construct(Media $folder, array $data)
    {
        $this->folder = $folder;
        $this->data = $data;
    }
}
