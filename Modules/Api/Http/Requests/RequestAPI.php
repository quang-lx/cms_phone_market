<?php

namespace Modules\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RequestAPI extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
            'data' => null,
            'error_code' => $validator->errors(),
        ], 0)
        );
    }
}
