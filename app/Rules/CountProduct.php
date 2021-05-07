<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CountProduct implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $product){
            if (!isset($product['count']) || $product['count'] <= 0){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vui lòng chọn số lượng từng vật tư bên dưới';
    }
}
