<?php


namespace LExpress\Info;


class OrderInfo
{
    /** @var string 商家订单号 */
    public $code = '';

    /** @var string 物流单号 */
    public $waybill = '';
    // 身份证号码
    public $idNumber = '';
    /** @var string 平台代码 */
    public $ecp_code = '3105961682';
    /** @var string 平台名称 */
    public $ecp_name = '上海寻梦信息技术有限公司';
    /** @var int 关区 */
    public $port;
}
