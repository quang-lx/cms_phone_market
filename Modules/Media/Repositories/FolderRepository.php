<?php
namespace Modules\Media\Repositories;


use Modules\Media\Entities\Media;
use Modules\Media\Collection\NestedFoldersCollection;
use Illuminate\Database\Eloquent\Collection;
use Modules\Mon\Repositories\BaseRepository;

interface FolderRepository extends BaseRepository {
    /**
     * Find a folder by its ID
     * @param int $folderId
     * @return Media|null
     */
    public function findFolder(int $folderId);

    /**
     * @param Media $folder
     * @return Collection
     */
    public function allChildrenOf(Media $folder);

    public function allNested() : NestedFoldersCollection;

    public function move(Media $folder, Media $destination) : Media;

    /**
     * Find the folder by ID or return a root folder
     * which is an instantiated File class
     * @param int $folderId
     * @return Media
     */
    public function findFolderOrRoot($folderId) : Media;
}
