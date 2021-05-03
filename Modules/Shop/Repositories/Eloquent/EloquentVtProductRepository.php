<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\VtProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\VtImportExcel;
use Modules\Mon\Entities\VtProduct;

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
        $data_import = VtImportExcel::find($import_excel_id)->vtImportProduct()->select('vt_product_id','vt_product_name','amount')->get()->toArray();
        foreach ($data_import as $key => $value) {
            $data = VtProduct::firstOrNew(['id' => $value['vt_product_id']]);
            $data->name = $value['vt_product_name'];
            $data->price = 0;
            $data->amount = ($data->amount + $value['amount']);
            $data->save();
        }
       
    }

}
