<?php

namespace LExpress\Sf;

use LExpress\AbstractConfig;

class Config extends AbstractConfig
{
    // 此处替换为您在丰桥平台获取的顾客编码
    public $partnerID = '';
    // 此处替换为您在丰桥平台获取的校验码
    public $checkword = '';
    // 是否沙盒环境
    public $isSBox = true;
    /** @var string 月结卡号  */
    public $monthlyCard = '';
    // 沙箱环境的地址
    public $sBoxUrl = 'https://sfapi.sit.sf-express.com:45273/std/service';
    // 生产环境的地址
    public $prodUrl = 'https://sfapi.sf-express.com/std/service';

    public $expressTypeId = 247;
    public $isGenWaybillNo = 0;
    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->isSBox ? $this->sBoxUrl : $this->prodUrl;
    }
}