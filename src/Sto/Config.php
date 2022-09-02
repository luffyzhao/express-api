<?php
namespace LExpress\Sto;

use LExpress\AbstractConfig;
use LExpress\ConfigInterFace;

class Config extends AbstractConfig
{
    /** @var string 结构化的业务报文体，可以是JSON或XML格式的字串（见下文表格及示例） */
    public $fromAppKey = 'sto_intl_test';
    /** @var string 订阅方/请求发起方的应用key */
    public $fromCode = 'sto_intl_code';
    /** @var string 订阅方/请求发起方的应用资源code */
    public $toAppKey = 'sto_oms';
    /** @var string sto_sortation */
    public $toCode = 'sto_oms';
    /** @var string sto_sortation */
    public $secretKey = 'intl123';

    /** @var string 请求地址 */
    public $apiUrl = 'http://cloudinter-linkgatewaytest.sto.cn/gateway/link.do';

    /** @var string 订单来源（订阅服务时填写的来源编码） */
    public $orderSource = 'MKDD';
    /** @var string 获取面单的类型（00-普通、03-国际、01-代收、02-到付、04-生鲜） */
    public $billType = '03';
    /** @var string 订单类型（01-普通订单、02-调度订单）无特殊业务，都传01 */
    public $orderType = '01';

    /** @var array  客户信息 测试环境示例（需调度才传月结编号）：{"siteCode":"666666","customerName":"666666000001","sitePwd":"abc123"} ；正式环境找网点或市场部申请 */
    public $customer = [
        'siteCode' => '666666',
        'customerName' => '666666000001',
        'sitePwd' => 'abc123',
    ];
    /** @var array 国际订单附属信息（国际订单必填） */
    public $internationalAnnex = [
        'internationalProductType' => '02',
        'customsDeclaration' => true,
        'senderCountry' => '中国',
        'receiverCountry' => '中国',
    ];
    /** @var int 支付方式（1-现付；2-到付；3-月结） */
    public $payModel = 3;
}
