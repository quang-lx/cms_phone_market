<?php

namespace Modules\Admin\Http\Controllers\Admin\Media;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller;
use Modules\Media\Entities\Media;
use Modules\Media\Http\Requests\UpdateMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\MediaRepository;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\AdminController;

class MediaController extends AdminController
{
    /**
     * @var MediaRepository
     */
    private $file;
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailsManager;

    public function __construct(Authentication $auth,MediaRepository $file, Repository $config, Imagy $imagy, ThumbnailManager $thumbnailsManager)
    {
		parent::__construct($auth);
        $this->file = $file;
        $this->config = $config;
        $this->imagy = $imagy;
        $this->thumbnailsManager = $thumbnailsManager;
    }

    public function index() : \Illuminate\View\View
    {
        $config = $this->config->get('asgard.media.config');

        return $this->view('admin::media.admin.index', compact('config'));
    }


    public function create()
    {
        return $this->view('admin::media.admin.create');
    }

    public function edit(Media $file)
    {
        $thumbnails = $this->thumbnailsManager->all();

        return $this->view('admin::media.admin.edit', compact('file', 'thumbnails'));
    }


    public function update(Media $file, UpdateMediaRequest $request)
    {
        $this->file->update($file, $request->all());

        return redirect()->route('admin.media.media.index')
            ->withSuccess(trans('media::messages.file updated'));
    }

    public function destroy(Media $file)
    {
        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);

        return redirect()->route('admin.media.media.index')
            ->withSuccess(trans('media::messages.file deleted'));
    }
}
