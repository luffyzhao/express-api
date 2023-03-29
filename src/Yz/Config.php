<?php

namespace LExpress\Yz;

use LExpress\AbstractConfig;

class Config extends AbstractConfig
{
    // 协议客户代码
    public $senderNo = "1100093827315";
    // 电商客户标识
    public $ecommerceUserId = "XYD";
    // 基础产品代码 1：标准快递 2：快递包裹 3：代收/到付（标准快递）
    public $baseProductNo = "1";
    public $msgType = "0";
    public $secretKey = 'da1c29a576632e982156f6f8351a79b9';
    public $url = "http://211.156.195.180/eis-itf-webext/uat_interface";
}