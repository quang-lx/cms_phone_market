<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\User;

class EloquentOrderRepository implements OrderRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;


    /** @var ShipTypeRepository */
    public $shipTypeRepo;

    /** @var AreaRepository */
    public $areaRepo;

    /** @var AddressRepository */
    public $addressRepo;

    public function __construct($model)
    {
        $this->model = $model;
        $this->shipTypeRepo = app(ShipTypeRepository::class);
        $this->areaRepo = app(AreaRepository::class);
        $this->addressRepo = app(AddressRepository::class);
    }

    protected function getShipProvince($province_id)
    {
        $provinces = $this->areaRepo->getProvinces();
        return $provinces->where('id', $province_id)->first();
    }

    protected function getShipDistrict(Request $request, $district_id)
    {
        $districts = $this->areaRepo->getDistricts($request);
        return $districts->where('id', $district_id)->first();
    }

    protected function getShipPhoenix(Request $request, $phoenix_id)
    {
        $phoenixs = $this->areaRepo->getPhoenixes($request);
        return $phoenixs->where('id', $phoenix_id)->first();
    }

    public function placeMultipleOrder(Request $request, User $user)
    {
        $orders = $request->get('orders');
        DB::beginTransaction();
        try {
            foreach ($orders as $order) {
                $quantity = $order['quantity'];
                $shipType = $this->shipTypeRepo->findById($request, $order['ship_type_id']);
                if (!$shipType) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }

                $shipAddress = $this->addressRepo->findById($request, $order['ship_address_id']);
                if (!$shipAddress) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }

                $shipProvince = $this->getShipProvince($shipAddress->province_id);
                if (!$shipProvince) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }

                $request->request->add(['province_id' => $shipAddress->province_id, 'district_id' => $shipAddress->phoenix_id]);

                $shipDistrict = $this->getShipDistrict($request, $shipAddress->district_id);
                if (!$shipDistrict) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }

                $shipPhoenix = $this->getShipPhoenix($request, $shipAddress->phoenix_id);
                if (!$shipPhoenix) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }
                $product = Product::find($order['product_id']);
                if (!$product) {
                    return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                }

                // validate so luong san pham
                $productAttributeValue = null;
                if (isset($order['product_attribute_value_id']) && !empty($order['product_attribute_value_id'])) {
                    $productAttributeValue = ProductAttributeValue::find($order['product_attribute_value_id']);
                    if (!$productAttributeValue) {
                        return [trans('api.messages.order.data invalid'), ErrorCode::ERR422];
                    }
                    if ($productAttributeValue->amount < $quantity) {
                        return [trans('api.messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                    }
                } else {
                    if ($product->amount < $quantity) {
                        return [trans('api.messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                    }
                }

                $orderResult = $this->placeOrder($order, $user, $shipType, $shipAddress, $shipProvince, $shipDistrict, $shipPhoenix, $product, $productAttributeValue);

            }
            DB::commit();

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return [trans('api.messages.order.internal server error'), ErrorCode::ERR500];
        }
        return true;

    }

    public function placeOrder($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix, Product $product,  $productAttributeValue)
    {
        $orderData = [];
        $orderProductData = [];
        $orderData['user_id'] = $user->id;
        $orderData['company_id'] = $user->company_id;
        $orderData['shop_id'] = $user->shop_id;
        $orderData['ship_type_id'] = $shipType->id;

        // ship
        $orderData['ship_address_id'] = $requestParams['ship_address_id'];
        $orderData['ship_type_id'] = $shipType->id;
        $orderData['ship_address'] = $shipType->address;
        $orderData['ship_province_id'] = $province->id;
        $orderData['ship_province_name'] = $province->name;

        $orderData['ship_district_id'] = $district->id;
        $orderData['ship_district_name'] = $district->name;

        $orderData['ship_phoenix_id'] = $phoenix->id;
        $orderData['ship_phoenix_name'] = $phoenix->name;


        $orderData['status'] = 0;
        $orderData['payment_status'] = 0;
        $orderData['order_type'] = $requestParams['order_type'];
        $orderData['ship_fee'] = 0;
        $orderData['discount'] = 0;

        $productPrice = $product->price;
        if ($product->price_sale) {
            $productPrice = $productPrice * (100 - $productPrice->price_sale) / 100;
        }
        $orderProductData['product_attribute_value_id'] = $requestParams['product_attribute_value_id'] ?? null;
        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['price'] = $product->price;
        $orderProductData['price_sale'] = $product->price_sale;
        $orderProductData['product_id'] = $product->id;
        $orderProductData['note'] = $requestParams['note'];

        if ($productAttributeValue) {
            $orderProductData['price'] = $productAttributeValue->price;
            $orderProductData['price_sale'] = $productAttributeValue->price_sale;

            $productPrice = $productAttributeValue->price;
            if ($productAttributeValue->price_sale) {
                $productPrice = $productPrice * (100 - $productAttributeValue->price_sale) / 100;
            }
        }
        //TODO price
        $orderData['total_price'] = $productPrice;
        $orderData['pay_price'] = $productPrice;

        $order = $this->model->create($orderData);
        if($order) {
            $orderProductData['order_id'] = $order->id;
            OrderProduct::create($order);
        }

        return true;

    }


}
