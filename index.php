<?php

use LExpress\Client;
use LExpress\Info;
use LExpress\Info\ProductInfo;
use LExpress\Info\ReceiverInfo;
use LExpress\Info\SenderInfo;
use LExpress\YTO\Config;
use LExpress\Yz\Create;

include_once __DIR__ . '/vendor/autoload.php';



$sfConfig = new Config();
$sfConfig->isSBox = true;
$client = new Client($sfConfig);

$order = new Info\OrderInfo();

$order->code = "1682576987212121";
$order->extra['buyerName'] = '索宇';
$order->extra['buyerIdNumber'] = '是小狗';

$product = new Info\ProductInfo();
$product->weight = 0.4;
$product->name = "护士装";
$product->qty = 1;
$product->price = 44;

$receiver = new ReceiverInfo();
$receiver->name = '路飞';
$receiver->mobile = "185421541225";
$receiver->province = '广东省';
$receiver->city = '深圳市';
$receiver->area = '南山区';
$receiver->address = '高新南七道粤美特大厦1506';

$senderInfo = new SenderInfo();
$senderInfo->name = '路飞';
$senderInfo->mobile = 18620313119;
$senderInfo->province = '广东省';
$senderInfo->city = '深圳市';
$senderInfo->area = '南山区';
$senderInfo->address = '高新南七道粤美特大厦1506';

$info = new Info();
$info->addProduct($product);
$info->setOrder($order);
$info->setReceiver($receiver);
$info->setSender($senderInfo);

//$client->create($info);
$response = $client->create($info);
$order->waybill = $response->getCode();
print_r($client->customs($info));
