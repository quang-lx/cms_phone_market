<?php

namespace Modules\Api\Repositories\Eloquent;

use App\Events\OrderStatusUpdated;
use App\Events\ShopNotiCreated;
use App\Events\UserUpdateOrderStatus;
use Carbon\Carbon;
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
use Modules\Mon\Entities\Cart;
use Modules\Mon\Entities\CartProduct;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\PaymentHistory;
use Modules\Mon\Entities\PaymentMethod;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\Voucher;
use Modules\Mon\Entities\VoucherProduct;
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

	        // noti cho shop
	        event(new OrderStatusUpdated([
		        'order_id' => $order->id,
		        'order_type' => $order->order_type,
		        'title' => $order->status_name,
		        'old_status' => $order->status,
		        'new_status' => $order->status,
		        'user_id' => null,
		        'shop_id' => $order->shop_id
	        ]));

	        $shopNotiArr = config(sprintf('shopnoti.shop_notifications.%s.%s', $order->order_type, $order->status), null);
	        if ($shopNotiArr && is_array($shopNotiArr)) {
		        event(new UserUpdateOrderStatus([
			        'order_id' => $order->id,
			        'title' => $shopNotiArr['title'],
			        'content' => sprintf($shopNotiArr['content'], $orderProductData['product_title']),
			        'shop_id' => $order->shop_id,
			        'order_status' => $order->status,
			        'order_type' => $order->order_type,
		        ]));
	        }

	        $data = [
		        'title' => trans('order.notifications.bao_hanh.title'),
		        'content' => trans('order.notifications.bao_hanh.content created', ['order_code' => $order->id]),
		        'fcm_token' => $user->fcm_token,
		        'order_id' => $order->id,
		        'type' => trans('order.notifications.bao_hanh.type', ['order_status' => Orders::STATUS_ORDER_CREATED]),
	        ];

	        event(new ShopNotiCreated($data));
        }

        return true;
    }

    public function placeOrderSuaChuaKhac($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix)
    {
        list($orderData, $orderProductData) = $this->parseOrderData($requestParams, $user, $shipType, $shipAddress, $province, $district, $phoenix);
        $orderProductData['product_title'] = $requestParams['product_title'];
        $orderData['shop_id'] = $requestParams['shop_id'];
        $orderData['type_other'] = 1;
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

            // noti cho shop
	        event(new OrderStatusUpdated([
		        'order_id' => $order->id,
		        'order_type' => $order->order_type,
		        'title' => $order->status_name,
		        'old_status' => $order->status,
		        'new_status' => $order->status,
		        'user_id' => null,
		        'shop_id' => $order->shop_id
	        ]));

	        $shopNotiArr = config(sprintf('shopnoti.shop_notifications.%s.%s', $order->order_type, $order->status), null);
	        if ($shopNotiArr && is_array($shopNotiArr)) {
		        event(new UserUpdateOrderStatus([
			        'order_id' => $order->id,
			        'title' => $shopNotiArr['title'],
			        'content' => sprintf($shopNotiArr['content'], $orderProductData['product_title']),
			        'shop_id' => $order->shop_id,
			        'order_status' => $order->status,
			        'order_type' => $order->order_type,
		        ]));
	        }
	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content created', ['order_code' => $order->id]),
		        'fcm_token' => $user->fcm_token,
		        'order_id' => $order->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CREATED]),
	        ];

	        event(new ShopNotiCreated($data));
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
        if ($product->sale_price) {
            $productPrice = $productPrice * (100 - $product->sale_price) / 100;
        }
        $orderProductData['product_attribute_value_id'] = $requestParams['product_attribute_value_id'] ?? null;
        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['price'] = $product->price;
        $orderProductData['price_sale'] = $productPrice;
        $orderProductData['product_id'] = $product->id;
        $orderProductData['note'] = $requestParams['note'] ?? '';

        if ($productAttributeValue) {

            $productPrice = $productAttributeValue->price;
            if ($productAttributeValue->sale_price) {
                $productPrice = $productPrice * (100 - $productAttributeValue->sale_price) / 100;
            }
            $orderProductData['price'] = $productAttributeValue->price;

            $orderProductData['price_sale'] =  $productPrice;
        }
        //TODO price
        $orderData['total_price'] = $productPrice;
        $orderData['pay_price'] = $productPrice;

        $order = $this->model->create($orderData);
        if ($order) {
            $orderProductData['order_id'] = $order->id;
            $orderProductData['product_img_id'] = optional($product->thumbnail)->id;
            $orderProduct = OrderProduct::create($orderProductData);
            if (!$orderProduct) {
                return false;
            }

	        // noti cho shop
	        event(new OrderStatusUpdated([
		        'order_id' => $order->id,
		        'order_type' => $order->order_type,
		        'title' => $order->status_name,
		        'old_status' => $order->status,
		        'new_status' => $order->status,
		        'user_id' => null,
		        'shop_id' => $order->shop_id
	        ]));

	        $shopNotiArr = config(sprintf('shopnoti.shop_notifications.%s.%s', $order->order_type, $order->status), null);
	        if ($shopNotiArr && is_array($shopNotiArr)) {
		        event(new UserUpdateOrderStatus([
			        'order_id' => $order->id,
			        'title' => $shopNotiArr['title'],
			        'content' => sprintf($shopNotiArr['content'], $orderProductData['product_title']),
			        'shop_id' => $order->shop_id,
			        'order_status' => $order->status,
			        'order_type' => $order->order_type,
		        ]));
	        }


	        $data = [
		        'title' => trans('order.notifications.sua_chua.title'),
		        'content' => trans('order.notifications.sua_chua.content created', ['order_code' => $order->id]),
		        'fcm_token' => $user->fcm_token,
		        'order_id' => $order->id,
		        'type' => trans('order.notifications.sua_chua.type', ['order_status' => Orders::STATUS_ORDER_CREATED]),
	        ];

	        event(new ShopNotiCreated($data));
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
        $orderData['ship_address'] = $shipAddress->address;
        $orderData['ship_fullname'] = $shipAddress->fullname;
        $orderData['ship_phone'] = $shipAddress->phone;
        $orderData['ship_province_id'] = $province->id;
        $orderData['ship_province_name'] = $province->name;

        $orderData['ship_district_id'] = $district->id;
        $orderData['ship_district_name'] = $district->name;

        $orderData['ship_phoenix_id'] = $phoenix->id;
        $orderData['ship_phoenix_name'] = $phoenix->name;


        $orderData['status'] = Orders::STATUS_ORDER_CREATED;
        $orderData['payment_status'] = 0;
        $orderData['order_type'] = $requestParams['order_type'];
        $orderData['ship_fee'] = 0;
        $orderData['discount'] = 0;

        $orderProductData['quantity'] = $requestParams['quantity']?? 0;
        $orderProductData['note'] = $requestParams['note'] ?? '';


        return [$orderData, $orderProductData];
    }

    public function parseOrderDataBaoHanh($requestParams, User $user, Product $product)
    {
        $orderData = [];
        $orderProductData = [];
        $orderData['user_id'] = $user->id;

        // ship


        $orderData['status'] = Orders::STATUS_ORDER_CREATED;
        $orderData['payment_status'] = 0;
        $orderData['order_type'] = $requestParams['order_type'];
        $orderData['ship_fee'] = 0;
        $orderData['discount'] = 0;

        $orderProductData['quantity'] = $requestParams['quantity'];
        $orderProductData['note'] = $requestParams['note'] ?? '';
        $orderProductData['product_id'] = $product->id;


        return [$orderData, $orderProductData];
    }

    public function listOrder(Request $request)
    {
        $user = Auth::user();
        $query = $this->model->newQuery()->where('user_id', $user->id)->with(['allOrderProducts', 'shop']);
        if ($type = $request->get('order_type')) {
            $query->where('order_type', $type);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        return $query->paginate($request->get('per_page', 10));

    }

    public function getSystemDiscountAmount(Request $request)
    {
        $voucherCode = $request->get('voucher_code');
        $products = $request->get('products');
        return $this->getVoucherAmount($voucherCode, null, $products);
    }

    public function getShopDiscountAmount(Request $request)
    {
        $voucherCode = $request->get('voucher_code');
        $shopId = $request->get('shop_id');
        $products = $request->get('products');
        return $this->getVoucherAmount($voucherCode, $shopId, $products);
    }

    protected function getVoucherAmount($voucherCode, $shopId, $products)
    {

        $voucher = Voucher::query()->where('code', $voucherCode)->first();
        if (!$voucher) {
            return [false, 'Mã giảm giá không tồn tại'];
        }
        if ($voucher->shop_id && $voucher->shop_id != $shopId) {
            return [false, 'Mã giảm giá không hợp lệ'];
        }
        if ($voucher->total_used + 1 > $voucher->total) {
            return [false, 'Mã giảm giá hết lượt sử dụng'];
        }

        if ($voucher->actived_at && $voucher->actived_at->gt(Carbon::now())) {
            return [false, 'Mã giảm giá hết hạn sử dụng'];
        }
        if ($voucher->expired_at && $voucher->expired_at->lt(Carbon::now())) {
            return [false, 'Mã giảm giá hết hạn sử dụng'];
        }

        $voucherTotalAmount = 0;

        foreach ($products as $product) {
            $productId = $product['id'] ?? null;
            $quantity = $product['quantity'];
            if (!$productId) {
                return [false, 'Chưa chọn sản phẩm'];
            }
            $model = Product::find($product['id']);
            $productAttributeValue = $product['product_attribute_id'] ?? null;
            $attributeModel = ProductAttributeValue::find($productAttributeValue);
            $voucherAmount = $this->calVoucherAmount($shopId, $voucher, $model, $attributeModel);
            if ($voucherAmount ) {
                $voucherTotalAmount += $voucherAmount * $quantity;
            }

        }
        return [$voucherTotalAmount, null];
    }

    protected function calVoucherAmount($shopId, Voucher $voucher, Product $product = null, ProductAttributeValue $productAttributeValue = null)
    {
        if ($voucher->shop_id && $voucher->shop_id != $shopId) {
            return false;
        }
        if ($voucher->total_used +1 > $voucher->total) {
            return false;
        }
        if ($voucher->type == Voucher::TYPE_DISCOUNT_PRODUCT) {
            if (!$product) {
                return false;
            }
            $checkProduct = VoucherProduct::query()->where([
                'voucher_id' => $voucher->id,
                'product_id' => $product->id,
            ])->count();
            if (!$checkProduct) {
                return false;
            }
        }
        if ($voucher->discount_type == Voucher::DISCOUNT_PRICE) {
            $voucherAmount = $voucher->discount_amount;
        } else {
            $productPrice = $product->price;
            if ($product->sale_price) {
                $productPrice = $productPrice * (100 - $product->sale_price) / 100;
            }
            if ($productAttributeValue) {

                $productPrice = $productAttributeValue->price;
                if ($productAttributeValue->sale_price) {
                    $productPrice = $productPrice * (100 - $productAttributeValue->sale_price) / 100;
                }
            }
            $voucherAmount = $voucher->discount_amount * $productPrice / 100;
        }

        return $voucherAmount;

    }

    public function placeMultipleOrderBuyProduct(Request $request, User $user)
    {
        $orders = $request->get('orders');
        $paymentMethodId = $request->get('payment_method_id');
        $cart = Cart::query()->where('user_id', $user->id)->first();

        DB::beginTransaction();
        try {
            $payAmount = 0;
            $paymentMethod = PaymentMethod::find($paymentMethodId);
            if (!$paymentMethod) {
                return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
            }
            $paymentHistory = PaymentHistory::create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'pay_amount' => 0,
                'pay_method_name' => $paymentMethod->name,
            ]);
            // Order to push noti
	        $orderModelCreated = [];
            foreach ($orders as $order) {

                $orderType = Orders::TYPE_MUA_HANG;
                $shopId = $order['shop_id'];
                $order['order_type'] = $orderType;
                $order['payment_history_id'] = $paymentHistory->id;
                $order['ship_type_id'] = $request->get('ship_type_id');
                $order['ship_address_id'] = $request->get('ship_address_id');
                $order['system_voucher_code'] = $request->get('system_voucher_code');

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
                $listProductId = $order['products']?? [];
                $orderProducts = [];
                foreach ($listProductId as $productData) {

                    $quantity = $productData['quantity'];
                    $productId = $productData['product_id'];
                    $product = Product::find($productId);
                    if (!$product) {
                        return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                    }

                    $order['company_id'] = $product->company_id;

                    $orderProductTmp = [];
                    $orderProductTmp['product'] = $product;
                    $orderProductTmp['attribute'] = null;
                    $orderProductTmp['quantity'] = $productData['quantity'];

                    // validate so luong san pham
                    $productAttributeValue = null;
                    if (isset($productData['product_attribute_value_id']) && !empty($productData['product_attribute_value_id'])) {
                        $productAttributeValue = ProductAttributeValue::find($productData['product_attribute_value_id']);
                        if (!$productAttributeValue) {
                            return [trans('api::messages.order.data invalid'), ErrorCode::ERR422];
                        }
                        if ($productAttributeValue->amount < $quantity) {
                            return [trans('api::messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                        }
                        $productAttributeValue->amount = $productAttributeValue->amount - $quantity;
                        $productAttributeValue->save();
                        $orderProductTmp['attribute'] = $productAttributeValue;
                    } else {
                        if ($product->amount < $quantity) {
                            return [trans('api::messages.order.product out of stock', ['name' => $product->name]), ErrorCode::ERR422];
                        }
						$product->amount = $product->amount - $quantity;
                        $product->save();
                    }

                    if ($cart) {
                    	if ($productAttributeValue) {
		                    CartProduct::query()->where('cart_id', $cart->id)
			                    ->where('product_id', $product->id)
			                    ->where('product_attribute_value_id', $productAttributeValue->id)->delete();
	                    } else {
		                    CartProduct::query()->where('cart_id', $cart->id)
			                    ->where('product_id', $product->id)->delete();
	                    }

					}

                    $orderProducts[]= $orderProductTmp;

                }


                $orderResult = $this->placeOrderMultiProduct($order, $orderProducts, $shopId, $user, $shipType, $shipAddress, $shipProvince, $shipDistrict, $shipPhoenix);
                if (! ($orderResult instanceof Orders)) {
                    throw new InternalErrorException('Tạo đơn hàng không thành công');
                }
                $orderModelCreated[] = $orderResult;
                $payAmount += $orderResult->pay_price;

            }
            $paymentHistory->pay_amount = $payAmount;
            $paymentHistory->save();

            // send noti
	        foreach ($orderModelCreated as $orderCreated) {
		        event(new OrderStatusUpdated([
			        'order_id' => $orderCreated->id,
			        'order_type' => $orderCreated->order_type,
			        'title' => $orderCreated->status_name,
			        'old_status' => $orderCreated->status,
			        'new_status' => $orderCreated->status,
			        'user_id' => null,
			        'shop_id' => $orderCreated->shop_id
		        ]));

		        $shopNotiArr = config(sprintf('shopnoti.shop_notifications.%s.%s', $orderCreated->order_type, $orderCreated->status), null);
		        if ($shopNotiArr && is_array($shopNotiArr)) {
		        	$orderProducts = $orderCreated->allOrderProducts->pluck('product_title')->toArray();
		        	Log::info($orderProducts);
			        event(new UserUpdateOrderStatus([
				        'order_id' => $orderCreated->id,
				        'title' => $shopNotiArr['title'],
				        'content' => sprintf($shopNotiArr['content'], implode(', ', $orderProducts)),
				        'shop_id' => $orderCreated->shop_id,
				        'order_status' => $orderCreated->status,
				        'order_type' => $orderCreated->order_type,
			        ]));
		        }

		        $data = [
			        'title' => trans('order.notifications.ban_hang.title'),
			        'content' => trans('order.notifications.ban_hang.content created', ['order_code' => $orderCreated->id]),
			        'fcm_token' => $user->fcm_token,
			        'order_id' => $orderCreated->id,
			        'type' => trans('order.notifications.ban_hang.type', ['order_status' => Orders::STATUS_ORDER_CREATED]),
		        ];

		        event(new ShopNotiCreated($data));
	        }


            DB::commit();


        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return [trans('api::messages.order.internal server error'), ErrorCode::ERR500];
        }
        return true;

    }

    public function placeOrderMultiProduct($requestParams, $products, $shopId, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix)
    {

        list($orderData, $orderProductData) = $this->parseOrderData($requestParams, $user, $shipType, $shipAddress, $province, $district, $phoenix);

        $orderData['company_id'] = $requestParams['company_id'];
        $orderData['payment_history_id'] = $requestParams['payment_history_id'];
        $orderData['shop_id'] = $requestParams['shop_id'];
        $order = $this->model->create($orderData);

        $totalPrice = 0;
        $payPrice = 0;
        $productsForVoucher = [];
        foreach ($products as $productData) {
            $product = $productData['product'];
            $productAttributeValue = $productData['attribute'];


            $productPrice = $product->price;
            if ($product->sale_price) {
                $productPrice = $productPrice * (100 - $product->sale_price) / 100;
            }

            $orderProductData['product_title'] = $product->name;

            $orderProductData['quantity'] = $productData['quantity'];
            $orderProductData['price'] = $product->price;
            $orderProductData['price_sale'] = $productPrice;
            $orderProductData['product_id'] = $product->id;
            $orderProductData['note'] = $requestParams['note'] ?? '';

            if ($productAttributeValue) {
				$orderProductData['product_attribute_value_id'] = $productAttributeValue->id;
                $productPrice = $productAttributeValue->price;
                if ($productAttributeValue->sale_price) {
                    $productPrice = $productPrice * (100 - $productAttributeValue->sale_price) / 100;
                }
                $orderProductData['price'] = $productAttributeValue->price;

                $orderProductData['price_sale'] =  $productPrice;
            }
            //TODO price
            $totalPrice += $productPrice*$productData['quantity'];

            if ($order) {
                $orderProductData['order_id'] = $order->id;
                $orderProductData['product_img_id'] = optional($product->thumbnail)->id;
                $orderProduct = OrderProduct::create($orderProductData);
                if (!$orderProduct) {
                    return false;
                }


            }
            $productsForVoucher[] = [
                'id' => $product->id,
                'quantity' => $productData['quantity'],
                'product_attribute_id' => $productData['product_attribute_id']?? null,
            ];
        }


        $shopVoucherCode = $requestParams['shop_voucher_code']?? null;
        $shopVoucherDiscount = 0;
        if ($shopVoucherCode) {
            $shopVoucher = Voucher::query()->where('code', $shopVoucherCode)->first();
            if ($shopVoucher->require_min_amount && $shopVoucher->require_min_amount > $totalPrice) {
                return -1;
            }
            list($shopVoucherDiscount) = $this->getVoucherAmount($shopVoucherCode, $shopId, $productsForVoucher);
            if ($shopVoucherDiscount) {
	            $order->shop_voucher_id = optional($shopVoucher)->id;
	            $order->shop_voucher_code = $shopVoucherCode;
	            $order->shop_discount = $shopVoucherDiscount?? 0;
	            $shopVoucher->total_used++;
	            $shopVoucher->save();
            }

        }

        $sysVoucherCode = $requestParams['system_voucher_code']?? null;
        $sysVoucherDiscount = 0;
        if ($sysVoucherCode) {
            $sysVoucher = Voucher::query()->where('code', $sysVoucherCode)->first();
            list($sysVoucherDiscount) = $this->getVoucherAmount($sysVoucherCode, $shopId, $productsForVoucher);

            if ($sysVoucherDiscount) {
	            $order->sys_voucher_id = optional($sysVoucher)->id;
	            $order->sys_voucher_code = $sysVoucherCode;
	            $order->sys_discount = $sysVoucherDiscount?? 0;

	            $sysVoucher->total_used++;
	            $sysVoucher->save();
            }
        }

        $order->discount = $shopVoucherDiscount + $sysVoucherDiscount;
        $order->ship_fee = 0;
        $order->total_price = $totalPrice;
        $order->pay_price = $totalPrice - $sysVoucherDiscount - $shopVoucherDiscount;
	    $order->pay_price = $order->pay_price > 0 ? $order->pay_price : 0;
        $order->save();


        return $order;

    }

	public function getDetail($id) {
    	return Orders::find($id);
	}
}
