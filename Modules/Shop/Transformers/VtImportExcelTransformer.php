<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VtImportExcelTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'filepath' => $this->filepath,
            'number_product' => $this->number_product,
            'status' => $this->status,

             'urls' => [
                'delete_url' => route('api.vtimportexcel.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
