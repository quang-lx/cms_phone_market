<?php

namespace Modules\Admin\Http\Controllers\Api\Rank;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Rank;
use Modules\Admin\Http\Requests\Rank\CreateRankRequest;
use Modules\Admin\Transformers\RankTransformer;
use Modules\Admin\Http\Requests\Rank\UpdateRankRequest;
use Modules\Admin\Repositories\RankRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class RankController extends ApiController
{
    /**
     * @var RankRepository
     */
    private $rankRepository;

    public function __construct(Authentication $auth, RankRepository $rank)
    {
        parent::__construct($auth);

        $this->rankRepository = $rank;
    }


    public function index(Request $request)
    {
        return RankTransformer::collection($this->rankRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return RankTransformer::collection($this->rankRepository->newQueryBuilder()->get());
    }


    public function store(CreateRankRequest $request)
    {
        $this->rankRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::rank.message.create success'),
        ]);
    }


    public function find(Rank $rank)
    {
        return new  RankTransformer($rank);
    }

    public function update(Rank $rank, UpdateRankRequest $request)
    {
        $this->rankRepository->update($rank, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::rank.message.update success'),
        ]);
    }

    public function destroy(Rank $rank)
    {
        $this->rankRepository->destroy($rank);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::rank.message.delete success'),
        ]);
    }
}
