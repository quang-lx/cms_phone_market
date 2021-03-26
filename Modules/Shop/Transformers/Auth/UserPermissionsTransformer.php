<?php
namespace Modules\Shop\Transformers\Auth;
use Illuminate\Http\Resources\Json\JsonResource;


class UserPermissionsTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,


        ];


        return $data;
    }

}
