<?php namespace Xeor\TinkoffMall\Classes;

use Log;
use Config;
use OFFLINE\Mall\Models\Order;
use OFFLINE\Mall\Models\PaymentGatewaySettings;
use OFFLINE\Mall\Classes\Payments\PaymentResult;
use OFFLINE\Mall\Classes\Payments\PaymentProvider;
use Xeor\TinkoffMall\Classes\TinkoffMerchantAPI;

/**
 * Process the payment via Tinkoff.
 */
class Tinkoff extends PaymentProvider
{
    /**
     * The order that is being paid.
     *
     * @var Order
     */
    public $order;
    /**
     * Data that is needed for the payment.
     * Card numbers, tokens, etc.
     *
     * @var array
     */
    public $data;

    /**
     * Return the display name of your payment provider.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Tinkoff';
    }

    /**
     * Return a unique identifier for this payment provider.
     *
     * @return string
     */
    public function identifier(): string
    {
        return 'tinkoff';
    }

    /**
     * Validate the given input data for this payment.
     *
     * @return bool
     * @throws \October\Rain\Exception\ValidationException
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * Return any custom backend settings fields.
     *
     * These fields will be rendered in the backend
     * settings page of your provider.
     *
     * @return array
     */
    public function settings(): array
    {
        return [];
    }

    /**
     * Setting keys returned from this method are stored encrypted.
     *
     * Use this to store API tokens and other secret data
     * that is needed for this PaymentProvider to work.
     *
     * @return array
     */
    public function encryptedSettings(): array
    {
        return [];
    }

    /**
     * Process the payment.
     *
     * @param PaymentResult $result
     *
     * @return PaymentResult
     */
    public function process(PaymentResult $result): PaymentResult
    {
        $response = null;

        $merchantId = Config::get('xeor.tinkoffmall::merchantId', '');
        $secretKey = Config::get('xeor.tinkoffmall::secretKey', '');


        $arrFields = $this->getReceipt();

        $Tinkoff = new TinkoffMerchantAPI($merchantId, $secretKey);

        $data = $Tinkoff->buildQuery('Init', $arrFields);
        $data = json_decode($data, true);

        if (!isset($data['PaymentURL'])) {
            Log::info('[tinkoffmall]: ' . var_export($data, true));
            return $result->fail($data, $response);
        }
        else {
//            Log::info('[tinkoffmall]: ' . var_export($data, true));
        }

        return $result->redirect($data['PaymentURL']);
    }

    /**
     * @return array
     */
    protected function getReceipt(): array
    {

        if (!$this->order)
            return [];

        $description = $this->getDescription();

        $arrFields = [
            'OrderId' => $this->order->order_number,
            'Amount' => (int)$this->order->getOriginal('total_post_taxes'),
            'Description' => $description,
            'DATA' => ['Email' => $this->order->customer->user->email, 'Connection_type' => 'mall',],
        ];

        //$checkDataTax = Config::get('xeor.tinkoffmall::checkDataTax', 0);
        $checkDataTax = 0;

        if ($checkDataTax) {
            $taxation = Config::get('xeor.tinkoffmall::taxation', 'error');
            $arrFields['Receipt'] = [
                'Email' => $this->order->customer->user->email,
                'Phone' => '', //TODO
                'Taxation' => $taxation,
                'Items' => $this->getReceiptItems(),
            ];
        }

        return $arrFields;
    }

    /**
     * @return string
     */
    protected function getDescription(): string
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getReceiptItems(): array
    {
        $receiptItems = [];
        $isShipping = false;
        $amount = $this->order->total_in_currency;

        return $this->balanceAmount($isShipping, $receiptItems, $amount);
    }

    /**
     * @return array
     */
    protected function balanceAmount($isShipping, $items, $amount): array
    {
        return $items;
    }
}
