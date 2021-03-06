<?php

namespace Modules\Media\Http\Controllers\Api;

use Modules\Media\Repositories\FolderRepository;
use Illuminate\Routing\Controller;

class AllNestableFolderController extends Controller
{
    /**
     * @var FolderRepository
     */
    private $folder;

    public function __construct(FolderRepository $folder)
    {
        $this->folder = $folder;
    }

    public function __invoke()
    {
        $array = [];
        $folders = $this->folder->allNested()->nest()->listsFlattened('filename', null, 0, $array, '--- ');

        return response()->json($folders);
    }
}
