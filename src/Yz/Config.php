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
    public $secretKey = '';
    public $url = "http://211.156.195.180/eis-itf-webext/interface";

    public $customsUrl = "http://222.240.147.33:17790/kjcktb_ws/ImportData";
}