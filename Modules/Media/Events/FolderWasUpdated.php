<?php

namespace Modules\Media\Events;

use Modules\Media\Entities\Media;

class FolderWasUpdated
{
    /**
     * @var Media
     */
    public $folder;
    /**
     * @var array
     */
    public $data;
    /**
     * @var array
     */
    public $previousFolderData;

    public function __construct(Media $folder, array $data, array $previousFolderData)
    {
        $this->folder = $folder;
        $this->data = $data;
        $this->previousFolderData = $previousFolderData;
    }
}
