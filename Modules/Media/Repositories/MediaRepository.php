<?php
namespace Modules\Media\Repositories;

use Modules\Media\Entities\Media;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Modules\Mon\Repositories\BaseRepository;

interface MediaRepository extends BaseRepository {
    /**
     * Create a file row from the given file
     * @param  UploadedFile $file
     * @param int $parentId
     * @return mixed
     */
    public function createFromFile(UploadedFile $file, $parentId = 0);

    /**
     * Find a file for the entity by zone
     * @param string $zone
     * @param object $entity
     * @return object
     */
    public function findFileByZoneForEntity($zone, $entity);

    /**
     * Find multiple files for the given zone and entity
     * @param string $zone
     * @param object $entity
     * @return object
     */
    public function findMultipleFilesByZoneForEntity($zone, $entity);

    /**
     * @param int $folderId
     * @return Collection
     */
    public function allChildrenOf(int $folderId) : Collection;

    public function findForVirtualPath(string $path);

    public function allForGrid() : Collection;

    public function move(Media $file, Media $destination) : Media;
}
