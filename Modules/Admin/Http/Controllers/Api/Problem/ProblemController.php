<?php

namespace Modules\Admin\Http\Controllers\Api\Problem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Problem;
use Modules\Admin\Http\Requests\Problem\CreateProblemRequest;
use Modules\Admin\Transformers\ProblemTransformer;
use Modules\Admin\Http\Requests\Problem\UpdateProblemRequest;
use Modules\Admin\Repositories\ProblemRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ProblemController extends ApiController
{
    /**
     * @var ProblemRepository
     */
    private $problemRepository;

    public function __construct(Authentication $auth, ProblemRepository $problem)
    {
        parent::__construct($auth);

        $this->problemRepository = $problem;
    }


    public function index(Request $request)
    {
        return ProblemTransformer::collection($this->problemRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ProblemTransformer::collection($this->problemRepository->newQueryBuilder()->get());
    }


    public function store(CreateProblemRequest $request)
    {
        $this->problemRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::problem.message.create success'),
        ]);
    }


    public function find(Problem $problem)
    {
        return new  ProblemTransformer($problem);
    }

    public function update(Problem $problem, UpdateProblemRequest $request)
    {
        $this->problemRepository->update($problem, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::problem.message.update success'),
        ]);
    }

    public function destroy(Problem $problem)
    {
        $this->problemRepository->destroy($problem);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::problem.message.delete success'),
        ]);
    }
}
