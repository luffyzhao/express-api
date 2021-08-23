<?php


namespace LExpress\Zto;


use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

class Create implements OperateInterFace
{
    /**
     * @var Config
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
     * @return Response
     * @author luffyzhao@vip.126.com
     */
    public function handle(): Response
    {
        $dataJson = $this->importBbcOrderReqBO();
        $request = new Request($this->config);
        return $request->handle(json_encode($dataJson), 'zto.intlbillorder.insert');
    }



    /**
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function importBbcOrderReqBO()
    {
        return [
            'logisticsno' => $this->data->getOrder()->waybill,
            'orderno' => $this->data->getOrder()->code,
            'shipper' => $this->data->getSender()->name,
            'shipperprov' => $this->data->getSender()->province,
            'shippercity' => $this->data->getSender()->city,
            'shipperdistrict' => $this->data->getSender()->area,
            'shipperaddress' => $this->data->getSender()->address,
            'shippermobile' => $this->data->getSender()->mobile,
            'shippertelephone' => '',
            'shippercountry' => "中国",
            'consignee' => $this->data->getReceiver()->name,
            'consigneeprov' => $this->data->getReceiver()->province,
            'consigneecity' => $this->data->getReceiver()->city,
            'consigneedistrict' => $this->data->getReceiver()->area,
            'consigneeaddress' => $this->data->getReceiver()->address,
            'consigneemobile' => $this->data->getReceiver()->mobile,
            'consigneetelephone' => '',
            'consigneecountry' => "中国",
            'idtype' => "1",
            'customerid' => $this->data->getOrder()->idNumber,
            'weight' => $this->data->getProductWeight() + 0.3,
            'ietype' => "I",
            'stockflag' => 2,
            'cumstomscode' => 'GZCUSTOMS',
            'platformSource' => $this->config->platformSource,
            'sortContent' => 'Y',
            'netweight' => $this->data->getProductWeight(),
            'shippingFee' => '',
            'shippingFeeUnit' => '',
            'insuranceFee' => '',
            'insuranceFeeUnit' => '',
            'shipType' => 'W',
            'warehouseCode' => $this->config->warehouseCode,
            'totallogisticsno' => '',
            'billEntity' => $this->billEntity(),
            'intlOrderItemList' => $this->intlOrderItemList(),
        ];
    }

    /**
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function intlOrderItemList()
    {
        $array = [];
        foreach ($this->data->getProducts() as $product) {
            $array[] = [
                'itemId' => $product->id,
                'itemName' => $product->name,
                'itemUnitPrice' => $product->price,
                'itemQuantity' => $product->qty,
                'currencyType' => 'CNY',
            ];
        }
        return $array;
    }

    /**
     * 清关信息
     * @return array
     * @author luffyzhao@vip.126.com
     */
    public function billEntity()
    {
        return [
            'ecpcode' => $this->data->getOrder()->ecp_code,
            'ecpname' => $this->data->getOrder()->ecp_name,
            'ecpcodeG' => '44199669AK',
            'ecpnameG' => '广东跨境达商贸有限公司',
            'quantity' => 1,//数量(不可为0,对应总署包裹数) N
            'wraptype' => '2',//包装种类(参考数据字典包装种类) N
            'batchnumbers' => '',//批次号 Y/N
            'companyCode' => $this->data->getOrder()->port,//批次号 Y/N
        ];
    }
}
