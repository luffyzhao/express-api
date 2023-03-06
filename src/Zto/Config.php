<?php

namespace LExpress\Zto;

use LExpress\AbstractConfig;

class Config extends AbstractConfig
{
    public $appcode = 2179;
    public $secretKey = '7FUhJjhN';
    public $warehouseCode = 'au002';
    public $customsCode = 'GZCUSTOMS';
    public $platformSource = 1336;

    // 测试
   // public $url = "https://izop-test.zt-express.com/oms/api";
    // 正式
    public $url = "https://izop.zt-express.com/oms/api";

}

/**
 *
{
"config_key": "ZTO-0002",
"customsAreaCode": "4921",
"appCode": "2179",
"secretKey": "7FUhJjhN",
"urlMethod": "addPddImportOrder"
}
 */