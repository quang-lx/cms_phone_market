<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\User;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

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
                $orderType = $order['order_type'];
                $quantity = $order['quantity'];
                // don hang sua chua
                if ($orderType == Orders::TYPE_BAO_HANH) {
                    $product = Product::find($order['product_id']);
                    if (!$product) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }
                    $orderResult = $this->placeOrderBaoHanh($order, $user, $product);
                    if (!$orderResult) {
                        throw new InternalErrorException('Tạo đơn hàng không thành công');
                    }
                } else {
                    $shipType = $this->shipTypeRepo->findById($request, $order['ship_type_id']);
                    if (!$shipType) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    $shipAddress = $this->addressRepo->findById($request, $order['ship_address_id']);
                    if (!$shipAddress) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    $shipProvince = $this->getShipProvince($shipAddress->province_id);
                    if (!$shipProvince) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    $request->request->add(['province_id' => $shipAddress->province_id, 'district_id' => $shipAddress->district_id]);

                    $shipDistrict = $this->getShipDistrict($request, $shipAddress->district_id);
                    if (!$shipDistrict) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    $shipPhoenix = $this->getShipPhoenix($request, $shipAddress->phoenix_id);
                    if (!$shipPhoenix) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    // check neu sua chua khac
                    $typeOther = $order['type_other'] ?? 0;

                    if ($typeOther && $orderType == Orders::TYPE_SUA_CHUA) {
                        $orderResult = $this->placeOrderSuaChuaKhac($order, $user, $shipType, $shipAddress, $shipProvince, $shipDistrict, $shipPhoenix);
                        if (!$orderResult) {
                            throw new InternalErrorException('Tạo đơn hàng không thành công');
                        }
                    } else {
                        $product = Product::find($order['product_id']);
                        if (!$product) {
                            return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                        }


                        // validate so luong san pham
                        $productAttributeValue = null;
                        if (isset($order['product_attribute_value_id']) && !empty($order['product_attribute_value_id'])) {
                            $productAttributeValue = ProductAttributeValue::find($order['product_attribute_value_id']);
                            if (!$productAttributeValue) {
                                return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                            }
                            if ($productAttributeValue->amount < $quantity) {
                                return [trans('api::messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                            }
                        } else {
                            if ($product->amount < $quantity) {
                                return [trans('api::messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                            }
                        }

                        $orderResult = $this->placeOrder($order, $user, $shipType, $shipAddress, $shipProvince, $shipDistrict, $shipPhoenix, $product, $productAttributeValue);
                        if (!$orderResult) {
                            throw new InternalErrorException('Tạo đơn hàng không thành công');
                        }
                    }
                }


            }
            DB::commit();

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return [trans('api::messages.order.internal server error'), ErrorCode::ERR500];
        }
        return true;

    }

    public function syncMedia($requestParams, $orderProduct)
    {
        $files = $requestParams['files'] ?? null;
        if ($files && is_array($files)) {
            foreach ($files as $file) {
                $zone = 'image';
                $fileSync[$file] = ['mediable_type' => get_class($orderProduct), 'zone' => $zone];
                $orderProduct->filesByZone($zone)->sync($fileSync);

            }
        }
    }

    public function placeOrderBaoHanh($requestParams, User $user, Product $product)
    {
        list($orderData, $orderProductData) = $this->parseOrderDataBaoHanh($requestParams, $user, $product);
        $orderProductData['product_title'] = $product->name;
        $orderData['company_id'] = $product->company_id;
        $orderData['shop_id'] = $product->shop_id;

        $order = $this->model->create($orderData);
        if ($order) {
            $orderProductData['order_id'] = $order->id;
            $orderProduct = OrderProduct::create($orderProductData);
            if (!$orderProduct) {
                return false;
            }
            $this->syncMedia($requestParams, $orderProduct);
        }

        return true;
    }

    public function placeOrderSuaChuaKhac($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix)
    {
        list($orderData, $orderProductData) = $this->parseOrderData($requestParams, $user, $shipType, $shipAddress, $province, $district, $phoenix);
        $orderProductData['product_title'] = $requestParams['product_title'];
        $orderData['shop_id'] = $requestParams['shop_id'];
        $shop = Shop::find($requestParams['shop_id']);
        if (!$shop) {
            return false;
        }
        $orderData['company_id'] = $shop->company_id;
        $order = $this->model->create($orderData);
        if ($order) {
            $orderProductData['order_id'] = $order->id;
            $orderProduct = OrderProduct::create($orderProductData);
            if (!$orderProduct) {
                return false;
            }
            $this->syncMedia($requestParams, $orderProduct);
        }

        return true;
    }

    public function placeOrder($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix, Product $product, $productAttributeValue)
    {

        list($orderData, $orderProductData) = $this->parseOrderData($requestParams, $user, $shipType, $shipAddress, $province, $district, $phoenix);
        $orderProductData['product_title'] = $product->name;
        $orderData['company_id'] = $product->company_id;
        $orderData['shop_id'] = $product->shop_id;
        $productPrice = $product->price;
        if ($product->price_sale) {
            $productPrice = $productPrice * (100 - $productPrice->price_sale) / 100;
        }
        $orderProductData['product_attribute_value_id'] = $requestParams['product_attribute_value_id'] ?? null;
        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['price'] = $product->price;
        $orderProductData['price_sale'] = $product->price_sale ?? 0;
        $orderProductData['product_id'] = $product->id;
        $orderProductData['note'] = $requestParams['note'] ?? '';

        if ($productAttributeValue) {
            $orderProductData['price'] = $productAttributeValue->price;
            $orderProductData['price_sale'] = $productAttributeValue->price_sale ?? 0;

            $productPrice = $productAttributeValue->price;
            if ($productAttributeValue->price_sale) {
                $productPrice = $productPrice * (100 - $productAttributeValue->price_sale) / 100;
            }
        }
        //TODO price
        $orderData['total_price'] = $productPrice;
        $orderData['pay_price'] = $productPrice;

        $order = $this->model->create($orderData);
        if ($order) {
            $orderProductData['order_id'] = $order->id;
            $orderProductData['product_img_id']  = optional($product->thumbnail)->id;
            $orderProduct = OrderProduct::create($orderProductData);
            if (!$orderProduct) {
                return false;
            }


        }

        return true;

    }

    public function parseOrderData($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix)
    {
        $orderData = [];
        $orderProductData = [];
        $orderData['user_id'] = $user->id;

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

        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['note'] = $requestParams['note'] ?? '';


        return [$orderData, $orderProductData];
    }

    public function parseOrderDataBaoHanh($requestParams, User $user, Product $product)
    {
        $orderData = [];
        $orderProductData = [];
        $orderData['user_id'] = $user->id;

        // ship


        $orderData['status'] = 0;
        $orderData['payment_status'] = 0;
        $orderData['order_type'] = $requestParams['order_type'];
        $orderData['ship_fee'] = 0;
        $orderData['discount'] = 0;

        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['note'] = $requestParams['note'] ?? '';


        return [$orderData, $orderProductData];
    }

    public function listOrder(Request $request) {
        $user = Auth::user();
        $query = $this->model->newQuery()->where('user_id', $user->id)->with(['allOrderProducts', 'shop']);
        if ($type = $request->get('order_type')) {
            $query->where('order_type', $type);
        }
        if($status = $request->get('status')) {
            $query->where('status', $status);
        }
        return $query->paginate($request->get('per_page', 10));

    }
}
