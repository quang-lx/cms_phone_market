<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface ProductRepository
{
    public function listByCategory(Request $request, $includeSub = false);

    public function listByService(Request $request);

    public function listBaoHanh(Request $request);
    public function buyNow(Request $request);
    public function bestSell(Request $request);

    public function detail($id);

    public function getRelated($id, Request $request);

    public function getSuggested($id, Request $request);

    public function listByShop(Request $request, $shop_id);

}
