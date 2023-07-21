<?php

namespace LExpress\YTO;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $customerCode = "K21000119";

    public $clientKey = "YT07300001";

    public $clientSecret = "yK5fknTTn7C6Ae71dh6B7mNHLWsGCIYELxb8sDioX5p";

    public $channelCode = "CN073101";

    public $secretKey = "u2Z1F7Fh";

    public $version = 'v1';

    public $logisticsCode = '331698Z002';
    public $logisticsName = '上海圆通国际货物运输代理有限公司杭州分公司';
    // 是否沙盒环境
    public $isSBox = true;

    public $appType = 1;
}