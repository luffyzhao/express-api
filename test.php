<?php

use LExpress\Info;
use LExpress\Sf\Config;

include_once __DIR__ . '/vendor/autoload.php';

$sfConfig = new Config();
$sfConfig->partnerID = 'BLGYLdx1Fguw';
$sfConfig->checkword = 'ovMIF3sQ2gJ48UzGy9SsOX2LSbblihgC';
$sfConfig->monthlyCard = '7551234567';
$sfConfig->isSBox = true;
$sfConfig->isGenWaybillNo = 1;

$info = new Info();

$order = new Info\OrderInfo();
$order->waybill = '279538138350';
$order->extra['waybillNoCheckValue'] = '867555';

$info->setOrder($order);

$pdf = new \LExpress\Sf\GetPdf($sfConfig, $info);
print_r($pdf->handle());

// echo (json_encode($pdf->getBody()));
