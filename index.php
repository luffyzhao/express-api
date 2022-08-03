<?php

use LExpress\Client;
use LExpress\Info;
use LExpress\Info\ProductInfo;
use LExpress\Info\ReceiverInfo;
use LExpress\Info\SenderInfo;
use LExpress\Sf\Config;
use LExpress\Zto\Create;

include_once __DIR__ . '/vendor/autoload.php';

$sfConfig = new Config();
$sfConfig->partnerID = '';
$sfConfig->checkword = '';
$sfConfig->monthlyCard = '';

$client = new Client($sfConfig);

$order = new Info\OrderInfo();
$order->code = "XP1121081912345678912345678912";
$order->waybill = '194772827267';

$product = new Info\ProductInfo();
$product->id = 1;
$product->weight = 0.4;
$product->name = "护士装";
$product->qty = 1;
$product->price = 44;

$receiver = new ReceiverInfo();
$receiver->name = '~AAAAAAEdb1IAAAAAAAC/zhVuhts2g5BWtQpfA7Ktz70=~0~';
$receiver->mobile = "~AAAAAAEdb1IAAAAAAACT6bFL0RNXDLOMrib1KHCqGzY=~0~";
$receiver->province = '湖南省';
$receiver->city = '长沙市';
$receiver->area = '长沙县';
$receiver->address = '~AAAAAAEdb1IAAAAAAADfXyt3ZlDgwK03do+E41wxPa7YO9lmbNKoDo2DshOiifrvpN+e75OdXg+Vu7mdmE97UQ==~0~';

$senderInfo = new SenderInfo();
$senderInfo->name = '路飞';
$senderInfo->mobile = 18620313779;
$senderInfo->province = '湖南省';
$senderInfo->city = '长沙市';
$senderInfo->area = '长沙县';
$senderInfo->address = '黄花综合保税区';

$info = new Info();
$info->addProduct($product);
$info->setOrder($order);
$info->setReceiver($receiver);
$info->setSender($senderInfo);

$response = $client->getBody($info);

print_r($response);
echo \GuzzleHttp\json_encode($response, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "\n";