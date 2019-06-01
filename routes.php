<?php

use Xeor\TinkoffMall\Classes\Tinkoff;
use OFFLINE\Mall\Classes\Payments\PaymentResult;
use OFFLINE\Mall\Models\Order;

Route::post('tinkoff/notify', function () {
    $status = 'NOTOK';
    $response = null;
    $request = Request::getContent();
    $data = json_decode($request, true);

    $orderId = (int)$data['OrderId'];
    $order = Order::find($orderId);

    if ($order && !$order->getIsPaidAttribute()) {
        $tinkoff = new Tinkoff();

        $result = new PaymentResult($tinkoff, $order);
        if ($data['Success']) {
            $result->success($data, $response);
        }
        else {
            $result->fail($data, $response);
        }

        $status = 'OK';
    }
    return $status;
});
