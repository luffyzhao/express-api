<?php

namespace LExpress\Zto;

use LExpress\AbstractConfig;

/**
 * @todo https://open.zt-express.com/#/doc/-1
 */
class Config extends AbstractConfig
{
    public $appcode = 0;
    public $secretKey = '';
    public $warehouseCode = '';
    public $customsCode = '';
    public $platformSource = 0;

    // 测试
   // public $url = "https://izop-test.zt-express.com/oms/api";
    // 正式
    public $url = "https://izop.zt-express.com/oms/api";

}