<?php

namespace Modules\Mon\Services;


use Carbon\Carbon;

class OlavuiCarbonUtils
{
    public function convertDateTimeFromCms($input, $format='Y-m-d H:i:s') {
        if (empty($input)) return $input;
        return Carbon::createFromFormat($format,$input)->format('Y-m-d H:i:s');
    }
    public function convertDatetimeToDisplayCms($input, $format = 'Y-m-d H:i:s') {
        if (empty($input)) return $input;
        return Carbon::createFromFormat($format,$input)->format('Y-m-d H:i:s');
    }
}