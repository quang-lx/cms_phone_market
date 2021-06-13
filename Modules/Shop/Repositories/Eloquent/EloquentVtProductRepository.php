<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\VtProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\VtImportExcel;
use Modules\Mon\Entities\VtProduct;
use Illuminate\Support\Facades\Auth;

class EloquentVtProductRepository extends BaseRepository implements VtProductRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%");
                $q->orWhereHas('VtCategory', function ($c) use ($keyword) {
                    $c->where('name', 'LIKE', "%{$keyword}%");
                });
            });
        }

        if ($request->get('catId') !== null) {
            $catId = $request->get('catId');
            $query->where('vt_category_id', $catId);
        }

        $user = Auth::user();
		$query->where('company_id', $user->company_id);
		if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}


        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

    public function import($import_excel_id)
    {
        VtImportExcel::where('id', $import_excel_id)->update(['status' => 2]);;
        $data_import = VtImportExcel::find($import_excel_id)->vtImportProduct()->select('vt_product_id','amount')->get()->toArray();
        foreach ($data_import as $key => $value) {
            $data = VtProduct::find($value['vt_product_id']);
            $data->amount = ($data->amount + $value['amount']);
            $data->shop_id = Auth::user()->shop_id;
            $data->save();
        }
       
    }

}
