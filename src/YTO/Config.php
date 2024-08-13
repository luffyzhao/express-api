<?php

namespace LExpress\YTO;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    /** @var string 客户编码 */
    public $customerCode = "K9991024989";
    /** @var string 客户密钥 */
    public $secretKey = "u2Z1F7Fh";
    /** @var string 地址后缀 */
    public $urlPath = '/1FpbrP/K9991024989';
    public $version = 'v1';
    // 是否沙盒环境
    public $isSBox = true;

    /** @var string 跨境通关平台 ClientKey */
    public $clientKey = "YT07300001";
    /** @var string 跨境通关平台 ClientSecret */
    public $clientSecret = "yK5fknTTn7C6Ae71dh6B7mNHLWsGCIYELxb8sDioX5p";
    /** @var string 跨境通关平台 channelCode */
    public $channelCode = "CN073101";
    /** @var int 跨境运单申报appType */
    public $appType = 1;
    public $logisticsCode = '331698Z002';
    public $logisticsName = '上海圆通国际货物运输代理有限公司杭州分公司';
}