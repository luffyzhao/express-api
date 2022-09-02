<?php


namespace LExpress\Sto;


use function GuzzleHttp\json_encode as json_encodeAlias;
use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

class Create implements OperateInterFace
{
    /**
     * @var ConfigInterFace
     * @author luffyzhao@vip.126.com
     */
    private $config;
    /**
     * @var Info
     * @author luffyzhao@vip.126.com
     */
    private $data;

    /**
     * Create constructor.
     * @param Config $config
     * @param Info $data
     * @author luffyzhao@vip.126.com
     */
    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }


    /**
     * @author luffyzhao@vip.126.com
     */
    public function handle(): Response
    {
        $request = new Request($this->config);
        return $request->handle(json_encodeAlias($this->getContent()), 'OMS_EXPRESS_ORDER_CREATE');
    }


    /**
     * 请求主体
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getContent()
    {
        return array_merge($this->getOrderInfo(), ['sender' => $this->getSender(),
            'receiver' => $this->getReceiver(),
            'cargo' => $this->getCargo(),
            "extendFieldMap" => $this->getExtendFieldMap()
        ]);
    }

    /**
     * 订单信息
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getOrderInfo()
    {
        return [
            'orderNo' => $this->data->getOrder()->code,
            'orderSource' => $this->config->orderSource,
            'billType' => $this->config->billType,
            'orderType' => $this->config->orderType,
            'waybillNo' => $this->data->getOrder()->waybill,
            "customer" => $this->config->customer,
            'internationalAnnex' => $this->config->internationalAnnex,
            'payModel' => $this->config->payModel,
        ];
    }

    /**
     * 发件人信息
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getSender()
    {
        return [
            'name' => $this->data->getSender()->name,
            'mobile' => $this->data->getSender()->mobile,
            'province' => $this->data->getSender()->province,
            'city' => $this->data->getSender()->city,
            'area' => $this->data->getSender()->area,
            'address' => $this->data->getSender()->address,
        ];
    }

    /**
     * 收件人信息
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getReceiver()
    {
        return [
            'name' => $this->data->getReceiver()->name,
            'mobile' => $this->data->getReceiver()->mobile,
            'province' => $this->data->getReceiver()->province,
            'city' => $this->data->getReceiver()->city,
            'area' => $this->data->getReceiver()->area,
            'address' => $this->data->getReceiver()->address,
        ];
    }

    /**
     * 商品信息
     * @return array
     * @author luffyzhao@vip.126.com
     */
    public function getCargo()
    {
        return [
            'battery' => '10',
            'goodsType' => '小件',
            'goodsName' => $this->data->getProductName(),
            'goodsCount' => 1,
            'weight' => $this->data->getProductWeight(),
            'goodsAmount' => $this->data->getProductPrice()
        ];
    }

    /**
     * @return array
     * @author luffyzhao@vip.126.com
     */
    public function getExtendFieldMap()
    {
        return [
            "otherInfo" => [
                'appType' => '1',
                'appStatus' => '2',
                'freight' => '0',
                'billNo' => '',
                'insuredFee' => '0',
                'packNo' => '1',
                'goodsInfo' => $this->data->getProductName(),
                'currency' => '142',
                'note' => '',
            ]
        ];
    }

    public function getBody()
    {
        return $this->getContent();
    }
}
