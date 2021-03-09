<?php

namespace Modules\Media\Services\Movers;

use Modules\Media\Entities\Media;

class Mover
{
    /**
     * @var FileMover
     */
    private $fileMover;
    /**
     * @var FolderMover
     */
    private $folderMover;

    public function __construct(FileMover $fileMover, FolderMover $folderMover)
    {
        $this->fileMover = $fileMover;
        $this->folderMover = $folderMover;
    }

    public function move(Media $file, Media $destination) : int
    {
        $failedMoves = 0;

        if ($file->is_folder === false && $this->fileMover->move($file, $destination) === false) {
            $failedMoves++;
        }
        if ($file->is_folder === true && $this->folderMover->move($file, $destination) === false) {
            $failedMoves++;
        }

        return $failedMoves;
    }
}
