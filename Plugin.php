<?php namespace Xeor\TinkoffMall;

use Log;
use System\Classes\PluginBase;
use Xeor\TinkoffMall\Classes\Tinkoff;
use OFFLINE\Mall\Classes\Payments\PaymentGateway;

/**
 * TinkoffMall Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['Offline.Mall'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Тинькофф Банк',
            'description' => 'Проведение платежей через Tinkoff EACQ.',
            'author' => 'Sozonov Alexey',
            'icon' => 'icon-shopping-cart',
            'homepage' => 'https://sozonov-alexey.ru'
        ];
    }

    public function boot()
    {
        $gateway = $this->app->get(PaymentGateway::class);
        $gateway->registerProvider(new Tinkoff());
    }
}
