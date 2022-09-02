<?php


namespace LExpress\Info;


class OrderInfo extends AbstractInfo
{
    /** @var string 商家订单号 */
    public $code = '';

    /** @var string 物流单号 */
    public $waybill = '';
}
