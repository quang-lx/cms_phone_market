<?php

namespace Modules\Admin\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Mon\Entities\Rank;
use \Illuminate\Validation\Validator;

class UpdateAccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            'rank_point' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $point = $this->rank_point;
        $rankId = $this->rank_id;

        $result = Rank::find($rankId);
        $validator->after(function ($validator) use ($result, $point) {
            if ($point > $result->max_point || $point < $result->point){
                $msg = sprintf ('Hạng %s có khoảng điểm %s - %s', $result->name, $result->point, $result->max_point);
                $validator->errors()->add('rank_point', $msg);
            }

        });
        return $validator;                    
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'rank_point.required' => 'Điểm không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
