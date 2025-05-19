<?php
require 'vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Item;

MercadoPagoConfig::setAccessToken('APP_USR-3982508238201963-051814-8ffda4a23aa9ad9f9601086311b801c6-2444246037');

$item = new Item();
$item->title = 'Teste';
$item->quantity = 1;
$item->unit_price = 10;

$preference = new Preference();
$preference->items = [$item];
$preference->save();

echo $preference->id;