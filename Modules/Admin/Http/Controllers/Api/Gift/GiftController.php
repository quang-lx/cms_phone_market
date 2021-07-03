<?php

namespace Modules\Admin\Http\Controllers\Api\Gift;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Gift;
use Modules\Admin\Http\Requests\Gift\CreateGiftRequest;
use Modules\Admin\Transformers\GiftTransformer;
use Modules\Admin\Http\Requests\Gift\UpdateGiftRequest;
use Modules\Admin\Repositories\GiftRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class GiftController extends ApiController
{
    /**
     * @var GiftRepository
     */
    private $giftRepository;

    public function __construct(Authentication $auth, GiftRepository $gift)
    {
        parent::__construct($auth);

        $this->giftRepository = $gift;
    }


    public function index(Request $request)
    {
        return GiftTransformer::collection($this->giftRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return GiftTransformer::collection($this->giftRepository->newQueryBuilder()->get());
    }


    public function store(CreateGiftRequest $request)
    {
        $this->giftRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::gift.message.create success'),
        ]);
    }


    public function find(Gift $gift)
    {
        return new  GiftTransformer($gift);
    }

    public function update(Gift $gift, UpdateGiftRequest $request)
    {
        $this->giftRepository->update($gift, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::gift.message.update success'),
        ]);
    }

    public function destroy(Gift $gift)
    {
        $this->giftRepository->destroy($gift);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::gift.message.delete success'),
        ]);
    }
}
