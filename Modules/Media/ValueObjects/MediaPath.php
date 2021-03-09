<?php

namespace Modules\Media\ValueObjects;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaPath
{
    /**
     * @var string
     */
    private $path;

    public function __construct($path)
    {

        $this->path = $path;
    }

    /**
     * Get the URL depending on configured disk
     * @return string
     */
    public function getUrl()
    {
        $path = ltrim($this->path, '/');

        return Storage::disk(config('filesystems.default'))->url($path);
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->path;
    }

    public function __toString()
    {
        try {
            return $this->getUrl();
        } catch (\Exception $e) {
            return '';
        }
    }
}
