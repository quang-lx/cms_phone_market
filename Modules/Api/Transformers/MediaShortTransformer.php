<?php

namespace Modules\Api\Transformers;

use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaShortTransformer extends JsonResource
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
            'filename' => $this->filename,
            'extension' => $this->extension,
            'title' => $this->title,

            'path' => $this->getPath(),

            'media_type' => $this->media_type,

            'small_thumb' => url($this->imagy->getThumbnail($this->path, 's')),
            'medium_thumb' => url($this->imagy->getThumbnail($this->path, 'm')),
            'full_url' => url($this->getPath()),


        ];



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
