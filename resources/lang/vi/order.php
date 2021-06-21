<?php

return [

   'notifications' => [
       'sua_chua'=>[
           'content cancel' => 'Đơn hàng :order_code đã được hủy thành công.',
           'content confirm' => 'Đơn sửa chữa :order_code đã được xác nhận, vui lòng gửi hàng sửa chữa tới cửa hàng.',
           'content fixing' => 'Đơn sửa chữa :order_code đã đến được cửa hàng.',
           'content sending' => 'Đơn sửa chữa :order_code đã hoàn thành, vui lòng chờ cửa hàng trả sản phẩm.',
           'content done' => 'Đơn sửa chữa :order_code đã hoàn thành. Bạn hãy đánh giá sản phẩm giúp người khác hiểu hơn sản phẩm nhé.',
           'type' => 'suachua_:order_status',
           'title' => 'Đơn sửa chữa'
       ],
       'bao_hanh'=>[
            'content cancel' => 'Đơn hàng :order_code đã được hủy thành công.',
            'content confirm' => 'Đơn bảo hành :order_code đã được xác nhận, vui lòng gửi hàng bảo hành tới cửa hàng.',
            'content warranting' => 'Đơn bảo hành :order_code đã đến được cửa hàng.',
            'content sending' => 'Đơn bảo hành :order_code đã hoàn thành, vui lòng chờ cửa hàng trả sản phẩm.',
            'content done' => 'Đơn bảo hành :order_code đã hoàn thành. Bạn hãy đánh giá sản phẩm giúp người khác hiểu hơn sản phẩm nhé.',
            'type' => 'baohanh_:order_status',
            'title' => 'Đơn bảo hành'
       ],
       'ban_hang'=>[
            'content confirm' => 'Đơn hàng :order_code đã được xác nhận, vui lòng chờ người bán giao hàng',
            'content cancel' => 'Đơn hàng :order_code đã được hủy bởi người bán.',
            'content sending' => 'Đơn hàng :order_code đã hoàn thành. Bạn hãy đánh giá sản phẩm giúp người khác hiểu hơn sản phẩm nhé.',

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
