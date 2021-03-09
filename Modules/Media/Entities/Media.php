<?php

namespace Modules\Media\Entities;

use Modules\Media\Image\Facade\Imagy;
use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\ValueObjects\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Responsable;

class Media extends Model implements Responsable
{
    protected $table = 'medias';

    protected $fillable = ['filename', 'path', 'mimetype', 'extension', 'filesize', 'is_folder', 'folder_id', 'title', 'description'];
    protected $appends = ['path_string', 'media_type'];
    protected $casts = ['is_folder' => 'boolean',];
    /**
     * All the different images types where thumbnails should be created
     * @var array
     */
    private $imageExtensions = ['jpg', 'png', 'jpeg', 'gif'];

    public function getPathAttribute($value)
    {
        return new MediaPath($value);
    }

    public function getPathStringAttribute()
    {
        return (string) $this->path;
    }

    public function getMediaTypeAttribute()
    {
        return FileHelper::getTypeByMimetype($this->mimetype);
    }

    public function parent_folder()
    {
        return $this->belongsTo(__CLASS__, 'folder_id');
    }

    public function isFolder(): bool
    {
        return $this->is_folder;
    }

    public function isImage()
    {
        return in_array(pathinfo($this->path, PATHINFO_EXTENSION), $this->imageExtensions);
    }

    public function getThumbnail($type)
    {
        if ($this->isImage() && $this->getKey()) {
            return Imagy::getThumbnail($this->path, $type);
        }

        return false;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return response()
            ->file(public_path($this->path->getRelativeUrl()), [
                'Content-Type' => $this->mimetype,
            ]);
    }
}
