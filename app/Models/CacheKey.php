<?php


namespace App\Models;


class CacheKey
{
        const PROVINCE_ALL='PROVINCE_ALL';
        const DISTRICT_ALL='DISTRICT_ALL';
        const DISTRICT_BY_PROVINCE='DISTRICT_BY_PROVINCE_%s';
        const PHOENIX_ALL='PHOENIX_ALL';
        const PHOENIX_ALL_BY_DISTRICT='PHOENIX_BY_DISTRICT_%s';
        const AREA_ALL='AREA_ALL';

        const CATEGORY_PROBLEM='CATEGORY_%s_PROBLEM';
        const CATEGORY_BRAND='CATEGORY_%s_BRAND';

        const TAG_CATEGORY_PROBLEM = 'category_problem';
        const TAG_CATEGORY_BRAND = 'category_brand';


        const RANK_ALL = 'RANK_ALL';
        const RANK_DETAIL = 'RANK_%s_DETAIL';

        const SHIP_TYPE_ALL = 'SHIP_TYPE_ALL';
        const SHIP_TYPE_SHOP = 'SHIP_TYPE_SHOP_%s';

	const PAYMENT_METHOD_ALL = 'PAYMENT_METHOD_ALL';
	const PAYMENT_METHOD_SHOP = 'PAYMENT_METHOD_SHOP_%s';

}
