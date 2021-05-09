 <?php


if (!function_exists('insert_user_search')) {
    function insert_user_search($user_id, $fcm_token, $products) {

        $productIds = [];
        foreach ($products as $product) {
            $productIds [] = $product->id;
        }
        event(new \Modules\Api\Events\UserSearched($user_id, $fcm_token, $productIds));
    }
}

