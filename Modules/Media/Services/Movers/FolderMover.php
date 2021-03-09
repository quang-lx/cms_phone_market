<?php

namespace Modules\Media\Services\Movers;

use Modules\Media\Entities\Media;
use Illuminate\Contracts\Filesystem\Factory;
use League\Flysystem\FileExistsException;
use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Repositories\FolderRepository;

final class FolderMover implements MoverInterface
{
    /**
     * @var Factory
     */
    private $filesystem;
    /**
     * @var MediaRepository
     */
    private $file;
    /**
     * @var FolderRepository
     */
    private $folder;
    private $fromPath;
    private $toPath;

    public function __construct(Factory $filesystem, MediaRepository $file, FolderRepository $folder)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->folder = $folder;
    }

    public function move(Media $folder, Media $destination) : bool
    {
        $movedOnDisk = $this->moveOriginalOnDisk($folder, $destination);

        if ($movedOnDisk === false) {
            return false;
        }
        $folder = $this->moveDatabase($folder, $destination);

        $this->renameDatabaseReferences($folder);

        return true;
    }

    private function moveOriginalOnDisk(Media $folder, Media $destination) : bool
    {
        $this->fromPath = $folder->path->getRelativeUrl();
        $this->toPath = $this->getNewPathFor($folder->filename, $destination);

        return $this->moveDirectory($this->fromPath, $this->toPath);
    }

    private function moveDatabase(Media $folder, Media $destination) : Media
    {
        return $this->folder->move($folder, $destination);
    }

    private function renameDatabaseReferences(Media $folder)
    {
        $this->replacePathReferences($folder->id, $this->fromPath, $this->toPath);
    }

    private function replacePathReferences($folderId, $previousPath, $newPath)
    {
        $medias = $this->file->allChildrenOf($folderId);

        foreach ($medias as $media) {
            $oldPath = $media->path->getRelativeUrl();

            $media->update([
                'path' => $this->removeDoubleSlashes(str_replace($previousPath, $newPath, $oldPath)),
            ]);
            if ($media->isFolder() === true) {
                $this->replacePathReferences($media->id, $previousPath, $newPath);
            }
        }
    }

    private function moveDirectory($fromPath, $toPath) : bool
    {
        try {
            $this->filesystem->disk($this->getConfiguredFilesystem())
                ->move(
                    $this->getDestinationPath($fromPath),
                    $this->getDestinationPath($toPath)
                );
        } catch (FileExistsException $e) {
            return false;
        }

        return true;
    }

    private function getDestinationPath($path) : string
    {
        if ($this->getConfiguredFilesystem() === 'local') {
            return basename(public_path()) . $path;
        }

        return $path;
    }

    private function getConfiguredFilesystem() : string
    {
        return config('filesystems.default');
    }

    private function getNewPathFor(string $filename, Media $folder) : string
    {
        return $this->removeDoubleSlashes($folder->path->getRelativeUrl() . '/' . str_slug($filename));
    }

    private function removeDoubleSlashes(string $string) : string
    {
        return str_replace('//', '/', $string);
    }
}
