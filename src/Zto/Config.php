<?php

namespace LExpress\Zto;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $appcode = 10676;
    public $secretKey = '7r*cQSA#';
    public $warehouseCode = 'au002';
    public $customsCode = 'OTHERCUSTOMS';
    public $platformSource = 10676;

    // 测试
    public $url = "https://izop-test.zt-express.com/oms/api";
    // 正式
//    public $url = "https://izop.zt-express.com/oms/api";

}