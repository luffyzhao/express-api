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
$order->code = "QIAO-20200618-005";

$product = new Info\ProductInfo();
$product->id = 1;
$product->weight = 0.4;
$product->name = "护士装";
$product->qty = 1;
$product->price = 44;

$receiver = new ReceiverInfo();
$receiver->name = '路飞';
$receiver->mobile = 18620313779;
$receiver->province = '湖南省';
$receiver->city = '长沙市';
$receiver->area = '长沙县';
$receiver->address = '黄花综合保税区';

$senderInfo = new SenderInfo();
$senderInfo->name = '冯索宇';
$senderInfo->mobile = 15211163779;
$senderInfo->province = '湖南省';
$senderInfo->city = '长沙市';
$senderInfo->area = '长沙县';
$senderInfo->address = '星沙';

$info = new Info();
$info->addProduct($product);
$info->setOrder($order);
$info->setReceiver($receiver);
$info->setSender($senderInfo);

$response = $client->create($info);

print_r($response);