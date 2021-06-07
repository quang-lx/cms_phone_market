<?php
return [
	'shop_notifications' => [
		'sua_chua'=>[
			'created' =>  [
				'title' => 'Khách hàng đặt đơn sửa chữa thành công',
				'content' => 'Có đơn sửa chữa mới :order_id, vui lòng xác nhận đơn hàng',
			],
			'wait_client' =>  [
				'title' => 'Khách hàng xác nhận giá tiền và thời gian',
				'content' => 'Đơn sửa chữa :order_id đã được xác nhận, Vui lòng xác nhận sửa chữa',
			],

			'done' => [
				'title' => 'Đơn hàng đã hoàn thành',
				'content' => 'Đơn sửa chữa :order_id đã hoàn thành.',
			],
			'cancel' => [
				'title' => 'Có yêu cầu hủy đơn hàng từ khách hàng',
				'content' => 'Có yêu cầu hủy đơn sửa chữa :order_id. Vui lòng kiểm tra và xác nhận.',
			],
		],
		'bao_hanh'=>[
			'created' =>  [
				'title' => 'Khách hàng đặt đơn bảo hành thành công',
				'content' => 'Có đơn bảo hành mới :order_id đặt, vui lòng xác nhận đơn hàng',
			],

			'done' => [
				'title' => 'Đơn hàng đã hoàn thành',
				'content' => 'Đơn bảo hành :order_id đã hoàn thành.',
			],
			'cancel' => [
				'title' => 'Có yêu cầu hủy đơn hàng từ khách hàng',
				'content' => 'Có yêu cầu hủy đơn bảo hành :order_id. Vui lòng kiểm tra và xác nhận.',
			],
		],
		'mua_hang' => [
			'created' => [
				'title' => 'Khách hàng đặt đơn thành công',
				'content' => 'Có đơn hàng mới :order_id đặt, vui lòng xác nhận đơn hàng',
			],

			'done' => [
				'title' => 'Đơn hàng đã hoàn thành',
				'content' => 'Đơn hàng :order_id đã hoàn thành.',
			],
			'cancel' => [
				'title' => 'Có yêu cầu hủy đơn hàng từ khách hàng',
				'content' => 'Có yêu cầu hủy đơn hàng :order_id. Vui lòng kiểm tra và xác nhận.',
			],
		]
	],
];
