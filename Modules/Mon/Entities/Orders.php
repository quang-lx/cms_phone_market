<?php

namespace Modules\Mon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
	use  SoftDeletes;

	const TYPE_SUA_CHUA = 'sua_chua';
	const TYPE_BAO_HANH = 'bao_hanh';
	const TYPE_MUA_HANG = 'mua_hang';


	const STATUS_ORDER_CREATED = 'created'; // Chờ xác nhận
	const STATUS_ORDER_WAIT_CLIENT_CONFIRM = 'wait_client'; // Chờ xác nhận
	const STATUS_ORDER_CONFIRMED = 'confirmed';//Chờ nhận hàng
	const STATUS_ORDER_SENDING = 'sending';//Chờ giao hàng
	const STATUS_ORDER_FIXING = 'fixing'; //Chờ sửa chữa
	const STATUS_ORDER_WARRANTING = 'warranting';//Chờ bảo hành
	const STATUS_ORDER_DONE = 'done'; //Thành công
	const STATUS_ORDER_CANCEL = 'cancel'; //Đã hủy


	const PAYMENT_NOT_PAID = 0;
	const PAYMENT_PAID = 0;

	const ORDER_DONE_WAIT_CLIENT_CONFIRM = 0;
	const ORDER_DONE = 1;


	protected $table = 'orders';
	protected $fillable = [
		'id',
		'user_id',
		'company_id',
		'shop_id',
		'ship_type_id',
		'order_type',
		'status',
		'payment_status',
		'total_price',
		'discount',
		'ship_fee',
		'pay_price',
		'payment_history_id',

		'ship_address_id',
		'ship_province_id',
		'ship_province_name',
		'ship_district_id',
		'ship_district_name',
		'ship_phoenix_id',
		'ship_phoenix_name',
		'ship_address',
		'ship_fullname',
		'ship_phone',
		'created_at',
		'fix_time',
		'fix_time_date',
		'type_other',
		'shop_voucher_id',
		'shop_voucher_code',
		'shop_discount',
		'sys_voucher_id',
		'sys_voucher_code',
		'sys_discount',
		'shop_done',
		'shop_done_at'
	];

	public function allOrderProducts()
	{
		return $this->hasMany(OrderProduct::class, 'order_id');
	}
	public function firstOrderProduct()
	{
		return $this->belongsTo(OrderProduct::class, 'id', 'order_id');
	}
	public function orderProducts()
	{
		return $this->belongsTo(OrderProduct::class, 'id', 'order_id');
	}

	public function orderBuySellProduts()
	{
		return $this->hasMany(OrderProduct::class, 'order_id');
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class, 'shop_id');
	}

	public function company()
	{
		return $this->belongsTo(Company::class, 'company_id');
	}

	public function paymentHistory()
	{
		return $this->belongsTo(PaymentHistory::class, 'payment_history_id');
	}
	public function getThumbnailAttribute() {
		return $this->firstOrderProduct->thumbnail;
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function getStatusNameAttribute($value)
	{
		$statusName = '';
		switch ($this->status) {
			case self::STATUS_ORDER_CREATED:
				$statusName = 'Chờ xác nhận';
				break;
			case self::STATUS_ORDER_WAIT_CLIENT_CONFIRM:
				$statusName = 'Chờ xác nhận';
				break;
			case self::STATUS_ORDER_SENDING:
				$statusName = 'Chờ giao hàng';
				break;
			case self::STATUS_ORDER_CONFIRMED:
				$statusName = 'Chờ nhận hàng';
				break;
			case self::STATUS_ORDER_FIXING:
				$statusName = 'Chờ sửa chữa';
				break;
			case self::STATUS_ORDER_WARRANTING:
				$statusName = 'Chờ bảo hành';
				break;
			case self::STATUS_ORDER_DONE:
				$statusName = 'Thành công';
				break;
			case self::STATUS_ORDER_CANCEL:
				$statusName = 'Đã hủy';
				break;
		}
		return $statusName;
	}

	public static function getValidNextStatus()
	{
		return [
			self::TYPE_MUA_HANG => [

				self::STATUS_ORDER_CREATED => [
					self::STATUS_ORDER_SENDING,
					self::STATUS_ORDER_CANCEL
				],
				self::STATUS_ORDER_SENDING => [
					self::STATUS_ORDER_DONE
				]
			],
			self::TYPE_BAO_HANH => [
				self::STATUS_ORDER_CREATED => [
					self::STATUS_ORDER_CONFIRMED,
					self::STATUS_ORDER_CANCEL
				],

				self::STATUS_ORDER_CONFIRMED => [
					self::STATUS_ORDER_WARRANTING
				],
				self::STATUS_ORDER_WARRANTING => [
					self::STATUS_ORDER_SENDING
				],
				self::STATUS_ORDER_SENDING => [
					self::STATUS_ORDER_DONE
				]
			],
			self::TYPE_SUA_CHUA => [
				self::STATUS_ORDER_CREATED => [
					self::STATUS_ORDER_WAIT_CLIENT_CONFIRM,
					self::STATUS_ORDER_CONFIRMED,
					self::STATUS_ORDER_CANCEL
				],
				self::STATUS_ORDER_WAIT_CLIENT_CONFIRM => [
					self::STATUS_ORDER_CONFIRMED
				],
				self::STATUS_ORDER_CONFIRMED => [
					self::STATUS_ORDER_FIXING
				],
				self::STATUS_ORDER_FIXING => [
					self::STATUS_ORDER_SENDING
				],
				self::STATUS_ORDER_SENDING => [
					self::STATUS_ORDER_DONE
				]
			]
		];
	}
}
