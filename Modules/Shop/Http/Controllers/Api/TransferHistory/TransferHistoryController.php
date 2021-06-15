<?php

namespace Modules\Shop\Http\Controllers\Api\TransferHistory;

use App\Events\OrderStatusUpdated;
use App\Events\VtTransferHistoryCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
        $model = $this->transferhistoryRepository->create($request->all());
	    $shopNotiArr = config('shopnoti.shop_notifications.vt_transfer_history', null);
		if ($request->get('to_shop_id') && $request->get('type') == 2) {
			event(new VtTransferHistoryCreated([
				'order_id' => null,
				'title' => $shopNotiArr['title'],
				'content' => sprintf($shopNotiArr['content'], $model->getFromShopName()),
				'user_id' => Auth::user()->id,
				'shop_id' => $request->get('to_shop_id')
			]));
		}

        return response()->json([
            'errors' => false,
            'message' => trans('ch::transfer.message.create success'),
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
            'message' => trans('ch::transfer.message.update success'),
        ]);
    }

    public function destroy(TransferHistory $transferhistory)
    {
        $this->transferhistoryRepository->destroy($transferhistory);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::transfer.message.delete success'),
        ]);
    }
}
