<?php

namespace Modules\Shop\Http\Controllers\Api\VtImportExcel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtImportExcel;
use Modules\Shop\Http\Requests\VtImportExcel\CreateVtImportExcelRequest;
use Modules\Shop\Transformers\VtImportExcelTransformer;
use Modules\Shop\Http\Requests\VtImportExcel\UpdateVtImportExcelRequest;
use Modules\Shop\Repositories\VtImportExcelRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use App\Imports\ImportRecipes;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VtImportExcelController extends ApiController
{
    /**
     * @var VtImportExcelRepository
     */
    private $vtimportexcelRepository;

    public function __construct(Authentication $auth, VtImportExcelRepository $vtimportexcel)
    {
        parent::__construct($auth);

        $this->vtimportexcelRepository = $vtimportexcel;
    }


    public function index(Request $request)
    {
        return VtImportExcelTransformer::collection($this->vtimportexcelRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return VtImportExcelTransformer::collection($this->vtimportexcelRepository->newQueryBuilder()->get());
    }


    public function store(CreateVtImportExcelRequest $request)
    {
        try {
            DB::transaction(function () {
                $file = request()->file('file');
                $path = Storage::putFileAs('public/file-excel',$file,$file->getClientOriginalName());
                $dataImportExcel = [
                    'filepath' => $path,
                    'number_product' => 0,
                    'status' => 1,
                ];
                $importExcel = $this->vtimportexcelRepository->create($dataImportExcel);
                $import = new ImportRecipes($importExcel->id);
                Excel::import($import, request()->file('file'));
                $this->vtimportexcelRepository->update($importExcel, ['number_product' => $import->getRowCount()]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'errors' => true,
                'message' => 'Có lỗi xảy ra',
            ]);
        }


        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtimportexcel.message.create success'),
        ]);
    }


    public function find(VtImportExcel $vtimportexcel)
    {
        return new  VtImportExcelTransformer($vtimportexcel);
    }

    public function update(VtImportExcel $vtimportexcel, UpdateVtImportExcelRequest $request)
    {
        $this->vtimportexcelRepository->update($vtimportexcel, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtimportexcel.message.update success'),
        ]);
    }

    public function destroy(VtImportExcel $vtimportexcel)
    {
        $this->vtimportexcelRepository->destroy($vtimportexcel);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtimportexcel.message.delete success'),
        ]);
    }
}
