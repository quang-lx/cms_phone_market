<?php

namespace Modules\Shop\Http\Controllers\Api\TransferHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\TransferHistory;
use Modules\Shop\Http\Requests\TransferHistory\CreateTransferHistoryRequest;
use Modules\Shop\Transformers\TransferHistoryTransformer;
use Modules\Shop\Http\Requests\TransferHistory\UpdateTransferHistoryRequest;
use Modules\Shop\Repositories\TransferHistoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class TransferHistoryController extends ApiController
{
    /**
     * @var TransferHistoryRepository
     */
    private $transferhistoryRepository;

    public function __construct(Authentication $auth, TransferHistoryRepository $transferhistory)
    {
        parent::__construct($auth);

        $this->transferhistoryRepository = $transferhistory;
    }


    public function index(Request $request)
    {
        return TransferHistoryTransformer::collection($this->transferhistoryRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return TransferHistoryTransformer::collection($this->transferhistoryRepository->newQueryBuilder()->get());
    }


    public function store(CreateTransferHistoryRequest $request)
    {
        $this->transferhistoryRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::transferhistory.message.create success'),
        ]);
    }


    public function find(TransferHistory $transferhistory)
    {
        return new  TransferHistoryTransformer($transferhistory);
    }

    public function update(TransferHistory $transferhistory, UpdateTransferHistoryRequest $request)
    {
        $this->transferhistoryRepository->update($transferhistory, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::transferhistory.message.update success'),
        ]);
    }

    public function destroy(TransferHistory $transferhistory)
    {
        $this->transferhistoryRepository->destroy($transferhistory);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::transferhistory.message.delete success'),
        ]);
    }
}
