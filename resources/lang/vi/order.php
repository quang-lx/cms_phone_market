<?php

return [

   'notifications' => [
       'sua_chua'=>[
           'order created' => 'Đặt hàng thành công, vui lòng chờ người bán xác nhận đơn hàng :order_code',
           'content confirm' => 'Đơn sửa chữa :order_code đã được xác nhận, vui lòng gửi hàng sửa chữa tới cửa hàng.',
           'type' => 'suachua_:order_status',
           'title' => 'Đơn sửa chữa'
       ],
       'bao_hanh'=>[
            'content confirm' => 'Đơn bảo hành :order_code đã được xác nhận, vui lòng gửi hàng bảo hành tới cửa hàng.',
            'type' => 'baohanh_:order_status',
            'title' => 'Đơn bảo hành'
       ],
       'ban_hang'=>[
            'content confirm' => 'Đơn hàng :order_code đã được xác nhận, vui lòng chờ người bán giao hàng',
            'type' => 'banhang_:order_status',
            'title' => 'Đơn bán hàng'

        ]
   ],
    'order_status' => [
        'created' => 'Chờ xác nhận',
        'wait_client' => 'Chờ xác nhận',
        'confirmed' => 'Chờ nhận hàng',
        'fixing' => 'Chờ sửa chữa',
        'warranting' => 'Chờ bảo hành',
        'sending' => 'Chờ giao hàng',
        'done' => 'Thành công',
        'cancel' => 'Đã hủy',
    ],


];
