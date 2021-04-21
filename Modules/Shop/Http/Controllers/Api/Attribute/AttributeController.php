<?php

namespace Modules\Shop\Http\Controllers\Api\Attribute;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Attribute;
use Modules\Shop\Http\Requests\Attribute\CreateAttributeRequest;
use Modules\Shop\Transformers\AttributeTransformer;
use Modules\Shop\Http\Requests\Attribute\UpdateAttributeRequest;
use Modules\Shop\Repositories\AttributeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class AttributeController extends ApiController
{
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    public function __construct(Authentication $auth, AttributeRepository $attribute)
    {
        parent::__construct($auth);

        $this->attributeRepository = $attribute;
    }


    public function index(Request $request)
    {
        return AttributeTransformer::collection($this->attributeRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return AttributeTransformer::collection($this->attributeRepository->newQueryBuilder()->get());
    }


    public function store(CreateAttributeRequest $request)
    {
        $this->attributeRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::attribute.message.create success'),
        ]);
    }


    public function find(Attribute $attribute)
    {
        return new  AttributeTransformer($attribute);
    }

    public function update(Attribute $attribute, UpdateAttributeRequest $request)
    {
        $this->attributeRepository->update($attribute, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::attribute.message.update success'),
        ]);
    }

    public function destroy(Attribute $attribute)
    {
        $this->attributeRepository->destroy($attribute);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::attribute.message.delete success'),
        ]);
    }
}
