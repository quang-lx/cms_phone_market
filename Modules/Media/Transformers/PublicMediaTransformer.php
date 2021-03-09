<?php
namespace Modules\Media\Transformers;

use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMediaTransformer extends JsonResource
{
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailManager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->imagy = app(Imagy::class);
        $this->thumbnailManager = app(ThumbnailManager::class);
    }

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'mimetype' => $this->mimetype,
            'description' => $this->description,
            'filename' => $this->filename,
            'path' => $this->getPath(),
            'media_type' => $this->media_type,
            'created_at' => strtotime($this->created_at),
            'hideAfterInsert' => false,
            'full_url' => url($this->getPath()),
        ];

        foreach ($this->thumbnailManager->all() as $thumbnail) {
            $thumbnailName = $thumbnail->name();

            $data['thumbnails'][] = [
                'name' => $thumbnailName,
                'path' => $this->imagy->getThumbnail($this->path, $thumbnailName),
                'size' => $thumbnail->size(),
            ];
        }

        return $data;
    }

    private function getPath()
    {
        if ($this->is_folder) {
            return $this->path->getRelativeUrl();
        }

        return (string) $this->path;
    }
}
