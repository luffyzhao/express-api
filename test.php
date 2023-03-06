<?php

use LExpress\Info;
use LExpress\Sf\Config;

include_once __DIR__ . '/vendor/autoload.php';

$sfConfig = new Config();
$sfConfig->partnerID = 'BLGYLdx1Fguw';
$sfConfig->checkword = 'ZBSTYUnJIB4LzMi9pjCHRVWSRezKp1aa';
$sfConfig->monthlyCard = '7551234567';
$sfConfig->isSBox = false;
$sfConfig->isGenWaybillNo = 1;

$info = new Info();

$order = new Info\OrderInfo();
$order->waybill = '279538138350';
$order->extra['waybillNoCheckValue'] = substr('1867555', -6, 6);

$info->setOrder($order);

$pdf = new \LExpress\Sf\GetPdf($sfConfig, $info);
//print_r($pdf->handle()->getData());

 echo (json_encode($pdf->getBody()));
