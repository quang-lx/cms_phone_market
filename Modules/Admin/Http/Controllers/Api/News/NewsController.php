<?php

namespace Modules\Admin\Http\Controllers\Api\News;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Transformers\NewsTransformer;
use Modules\Mon\Entities\News;
use Modules\Admin\Http\Requests\News\CreateNewsRequest;
use Modules\Admin\Http\Requests\News\UpdateNewsRequest;
use Modules\Admin\Repositories\NewsRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Entities\NewsTag;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class NewsController extends ApiController
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(Authentication $auth, NewsRepository $news)
    {
        parent::__construct($auth);

        $this->newsRepository = $news;
    }


    public function index(Request $request)
    {
        return NewsTransformer::collection($this->newsRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return NewsTransformer::collection($this->newsRepository->newQueryBuilder()->get());
    }


    public function store(CreateNewsRequest $request)
    {
        $this->newsRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::news.message.create success'),
        ]);
    }


    public function find(News $news)
    {
        return new  NewsTransformer($news);
    }

    public function update(News $news, UpdateNewsRequest $request)
    {
        $this->newsRepository->update($news, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::news.message.update success'),
        ]);
    }

    public function destroy(News $news)
    {
        $this->newsRepository->destroy($news);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::news.message.delete success'),
        ]);
    }
}
