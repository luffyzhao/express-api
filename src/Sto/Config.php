<?php
namespace LExpress\Sto;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $fromAppKey = 'sto_intl_test';
    public $fromCode = 'sto_intl_code';
    public $toAppKey = 'sto_oms';
    public $toCode = 'sto_oms';
    public $secretKey = 'intl123';
    public $apiUrl = 'http://cloudinter-linkgatewaytest.sto.cn/gateway/link.do';

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    public function getNameSpace()
    {
        return "\\LExpress\\Sto";
    }
}
