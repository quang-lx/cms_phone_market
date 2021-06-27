<?php
require(__DIR__."/composers.php");
if (!function_exists('locales')) {
    /**
     * All active locales
     *
     * @return mixed
     */
    function locales()
    {
        return config('mon.locales');
    }
}

if (!function_exists('settings')) {
    /**
     * Get a setting
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    function settings($key, $default = null)
    {
        $keys = explode('.', $key);

        $category = $key;
        $key = '';
        $otherKey = '';

        if (count($keys) >= 2) {
            $category = array_shift($keys);
            $key = array_shift($keys);
            if (!empty($keys)) {
                $otherKey = implode('.', $keys);
            }
        }

        $values = \Modules\Mon\Support\Facades\Settings::get($category, $key);
        if ($values) {
            if ($otherKey !== '' && is_array($values)) {
                return \Illuminate\Support\Arr::get($values, $otherKey);
            }
            return $values;
        }
        return $default;
    }
}

if (!function_exists('locale_prefix')) {
    function locale_prefix()
    {
        $locale = \Illuminate\Support\Facades\Request::segment(1);
        return (config('mon.multiple_languages') && in_array($locale, locales()) && $locale != config('app.default_locale')) ? $locale : null;
    }
}

if (!function_exists('clean_html')) {
    /**
     * Clean the html tags
     *
     * @param $content
     * @return string
     */
    function clean_html($content)
    {
        return trim(html_entity_decode(strip_tags(preg_replace('#(?:<br\s*/?>\s*?){2,}#', ' ', nl2br($content)))));
    }
}

if (!function_exists('append_params_to_current_url')) {
    /**
     * Append the params to current url
     *
     * @param $params
     * @return string
     */
    function append_params_to_current_url($params)
    {
        //Retrieve current query strings:
        $currentQueries = \Illuminate\Support\Facades\Request::query();

        //Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $params);

        //Generate the URL with all the queries:
        return \Illuminate\Support\Facades\Request::fullUrlWithQuery($allQueries);
    }
}

if (!function_exists('supported_locales')) {
    function supported_locales()
    {
        $locales = locales();

        $supportLocales = [];
        foreach ($locales as $locale) {

            if ($localeData = config('locales.' . $locale)) {
                $supportLocales[$locale] = $localeData;
            }
        }
        return $supportLocales;
    }
}

if (!function_exists('current_locale')) {
    function current_locale()
    {
        return app()->getLocale();
    }
}

/**
 *
 * @param string $mobileNumber
 * @param int type format: 0: format 84xxx, 1: format 0xxxx, 2: format xxxx
 * @return String valid mobile
 */
if (!function_exists('validate_isdn')) {
    function validate_isdn($mobileNumber, $typeFormat = 0)
    {
        $valid_number = '';

        // Remove string "+"
        $mobileNumber = str_replace('+84', '84', $mobileNumber);

        if (preg_match('/^(84|0|)(92|188|186|52|56|58|99|199|59|96|97|98|162|163|164|165|166|167|168|169|32|33|34|35|36|37|38|39|91|94|123|124|125|127|129|83|84|85|86|81|82|87|90|93|120|121|122|126|128|70|79|77|76|78|88|89)\d{7}$/', $mobileNumber, $matches)) {
            /**
             * $typeFormat == 0: 8491xxxxxx
             * $typeFormat == 1: 091xxxxxx
             * $typeFormat == 2: 91xxxxxx
             */
            if ($typeFormat == 0) {
                if ($matches[1] == '0' || $matches[1] == '') {
                    $valid_number = preg_replace('/^(0|)/', '84', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            } else if ($typeFormat == 1) {
                if ($matches[1] == '84' || $matches[1] == '') {
                    $valid_number = preg_replace('/^(84|)/', '0', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            } else if ($typeFormat == 2) {
                if ($matches[1] == '84' || $matches[1] == '0') {
                    $valid_number = preg_replace('/^(84|0)/', '', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            }

        }
        return $valid_number;
    }
}

if (!function_exists('validate_mobile_number')) {
    function validate_mobile_number($mobileNumber, $typeFormat = 0)
    {
        $valid_number = '';

        // Remove string "+"
        $mobileNumber = str_replace('+84', '84', $mobileNumber);
        /**
         * $typeFormat == 0: 8491xxxxxx
         * $typeFormat == 1: 091xxxxxx
         * $typeFormat == 2: 91xxxxxx
         */
        if (preg_match('/^(84|0|)(92|188|186|52|56|58|99|199|59|96|97|98|162|163|164|165|166|167|168|169|32|33|34|35|36|37|38|39|91|94|123|124|125|127|129|83|84|85|86|81|82|87|90|93|120|121|122|126|128|70|79|77|76|78|88|89)\d+$/', $mobileNumber, $matches)) {
            if ($typeFormat == 0) {
                if ($matches[1] == '0' || $matches[1] == '') {
                    $valid_number = preg_replace('/^(0|)/', '84', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            } else if ($typeFormat == 1) {
                if ($matches[1] == '84' || $matches[1] == '') {
                    $valid_number = preg_replace('/^(84|)/', '0', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            } else if ($typeFormat == 2) {
                if ($matches[1] == '84' || $matches[1] == '0') {
                    $valid_number = preg_replace('/^(84|0)/', '', $mobileNumber);
                } else {
                    $valid_number = $mobileNumber;
                }
            }
        }

        return $valid_number;
    }
}

if (!function_exists('gen_sms_token')) {
    function gen_sms_token() {
        return 2021;
//        return mt_rand(1000,9999);
    }
}

if (!function_exists('map_distance_km')) {
    function map_distance_km($lat1, $long1, $lat2, $long2) {

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $long1 *= $pi80;
        $lat2 *= $pi80;
        $long2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $long2 - $long1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        //echo '<br/>'.$km;
        return $km;
    }
}

