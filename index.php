<?php

use LExpress\Client;
use LExpress\Info;
use LExpress\Info\ProductInfo;
use LExpress\Info\ReceiverInfo;
use LExpress\Info\SenderInfo;
use LExpress\JD\Config;
use LExpress\Yz\Create;

include_once __DIR__ . '/vendor/autoload.php';

$sfConfig = new Config();
$sfConfig->appKey = "";
$sfConfig->appSecret = "";
$sfConfig->accessToken = "";
$sfConfig->serverUrl = "https://api.jd.com/routerjson";

$client = new Client($sfConfig);

$order = new Info\OrderInfo();
//$order->code = "1682576987";
//$order->code = "16825769871";
//$order->code = "16825769872";
//$order->code = "168257698721";
//$order->code = "1682576987212";
//$order->waybill = "JDVE08448318980";
//
//$order->code = "16825769872122";
//$order->waybill = "JDVE08448640092";


$order->code = "1682576987212121";

$order->extra['salePlat'] = '0030001';

$order->extra['customerCode'] = '027K2698476';
$order->extra['platformId'] = '8041420';
$order->extra['platformName'] = '长沙斑鹿仓海应用';
$order->extra['buyerIdNumber'] = '430423199104034713';
$order->extra['customsId'] = 4921;
$order->extra['customsCode'] = 4900;
$order->extra['deptNo'] = 'EBU4418055054256';
$order->extra['isvSource'] = 'ISV0020008044873';

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
print_r($response = $client->create($info));
$order->waybill = $response->getCode();
print_r($client->customs($info));
