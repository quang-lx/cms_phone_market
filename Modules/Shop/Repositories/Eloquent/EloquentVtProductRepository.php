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
                $q->orWhere('code', 'LIKE', "%{$keyword}%");
                $q->orWhere('id',"$keyword");
            });
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
        $data_import = VtImportExcel::find($import_excel_id)->vtImportProduct()->whereNull('note')->select('vt_product_id','vt_product_name','amount')->get()->toArray();
        foreach ($data_import as $key => $value) {
            $data = VtProduct::firstOrNew(['id' => $value['vt_product_id']]);
            $data->name = $value['vt_product_name'];
            $data->price = 0;
            $data->company_id = Auth::user()->company_id;
            $data->shop_id = Auth::user()->shop_id;
            $data->amount = ($data->amount + $value['amount']);
            $data->save();
        }
       
    }

}
