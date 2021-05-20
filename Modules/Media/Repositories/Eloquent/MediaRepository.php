<?php
namespace Modules\Media\Repositories\Eloquent;

use Illuminate\Support\Str;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Media\Events\FileIsCreating;
use Modules\Media\Events\FileIsUpdating;
use Modules\Media\Events\FileStartedMoving;
use Modules\Media\Events\FileWasCreated;
use Modules\Media\Events\FileWasUpdated;
use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\Entities\Media;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaRepository extends BaseRepository implements \Modules\Media\Repositories\MediaRepository {

    public function __construct($model)
    {
        parent::__construct($model);
    }

    /**
     * Update a resource
     * @param  Media  $file
     * @param $data
     * @return mixed
     */
    public function update($file, $data)
    {
        event($event = new FileIsUpdating($file, $data));
        $file->update($event->getAttributes());
        event(new FileWasUpdated($file));
        return $file;
    }

    /**
     * Create a file row from the given file
     * @param  UploadedFile $file
     * @param int $parentId
     * @return mixed
     */
    public function createFromFile(UploadedFile $file, $parentId = 0)
    {
        $fileName = $file->getClientOriginalName();
        $extension = last(explode('.', $fileName));
        $fileName = Str::uuid().'.'. $extension;


        $data = [
            'filename' => $fileName,
            'path' => $this->getPathFor($fileName, $parentId),
            'extension' => substr(strrchr($fileName, '.'), 1),
            'mimetype' => $file->getClientMimeType(),
            'filesize' => $file->getFileInfo()->getSize(),
            'folder_id' => $parentId,
            'is_folder' => 0,
        ];

        event($event = new FileIsCreating($data));

        $file = $this->model->create($event->getAttributes());
        event(new FileWasCreated($file));

        return $file;
    }

	private function getPathFor(string $filename, int $folderId)
	{
		$month = now()->format('Ym');
		if ($folderId !== 0) {
			$parent = app(\Modules\Media\Repositories\FolderRepository::class)->findFolder($folderId);
			if ($parent !== null) {
				return $parent->path->getRelativeUrl() . '/'.$month.'/' . $filename;
			}
		}


		return '/media/'.$month.'/' . $filename;
	}

    public function destroy($file)
    {
        $file->delete();
    }

    /**
     * Find a file for the entity by zone
     * @param $zone
     * @param object $entity
     * @return object
     */
    public function findFileByZoneForEntity($zone, $entity)
    {
        foreach ($entity->files as $file) {
            if ($file->pivot->zone == $zone) {
                return $file;
            }
        }

        return '';
    }

    /**
     * Find multiple files for the given zone and entity
     * @param zone $zone
     * @param object $entity
     * @return object
     */
    public function findMultipleFilesByZoneForEntity($zone, $entity)
    {
        $files = [];
        foreach ($entity->files as $file) {
            if ($file->pivot->zone == $zone) {
                $files[] = $file;
            }
        }

        return new Collection($files);
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getNewUniqueFilename($fileName)
    {
        $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $models = $this->model->where('filename', 'LIKE', "$fileNameOnly%")->get();

        $versionCurrent = $models->reduce(function ($carry, $model) {
            $latestFilename = pathinfo($model->filename, PATHINFO_FILENAME);

            if (preg_match('/_([0-9]+)$/', $latestFilename, $matches) !== 1) {
                return $carry;
            }

            $version = (int)$matches[1];

            return ($version > $carry) ? $version : $carry;
        }, 0);

        return $fileNameOnly . '_' . ($versionCurrent+1) . '.' . $extension;
    }

    /**
     * @param Request $request
     * @param null $relations
     * @return mixed
     */
    public function serverPagingFor(Request $request, $relations = null)
    {
        $media = $this->newQueryBuilder();
        if ($relations) {
            $media = $media->with($relations);
        }

        $media->orderBy('is_folder', 'desc');
        $media->where('folder_id', $request->get('folder_id', 0));

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $media->where('filename', 'LIKE', "%{$term}%");
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $media->orderBy($request->get('order_by'), $order);
        } else {
            $media->orderBy('created_at', 'desc');
        }

        return $media->paginate($request->get('per_page', 10));
    }

    /**
     * @param int $folderId
     * @return Collection
     */
    public function allChildrenOf(int $folderId): Collection
    {
        return $this->model->where('folder_id', $folderId)->get();
    }

    public function findForVirtualPath(string $path)
    {
        $prefix = 'media';

        return $this->model->where('path', $prefix . $path)->first();
    }

    public function allForGrid(): Collection
    {
        return $this->model->where('is_folder', 0)->get();
    }

    public function move(Media $file, Media $destination) : Media
    {
        $previousData = [
            'filename' => $file->filename,
            'path' => $file->path,
        ];

        $this->update($file, [
            'path' => $this->getPathFor($file->filename, $destination->id),
            'folder_id' => $destination->id,
        ]);

        event(new FileStartedMoving($file, $previousData));

        return $file;
    }
}
