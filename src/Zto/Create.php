<?php

namespace LExpress\Zto;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;
use Ramsey\Uuid\Uuid;

class Create implements OperateInterFace
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Info
     */
    private $data;

    /**
     * @param ConfigInterFace $config
     * @param Info $data
     */
    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): Response
    {
        $verify = $this->verifyBigBen();
        if ($verify->getStatus()) {
            $request = new Request($this->config);
            $res = $request->handle($this->getBody(), 'addBbcImportOrder');
            if($res->getStatus()){
                $res->addData($verify->getData());
                $res->setCode($res->getData()['logisticsId']);
                return $res;
            }
            return $res;
        }
        return $verify;
    }

    /**
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function verifyBigBen()
    {
        $request = new Request($this->config);
        return $request->handle($this->bigBenBody(), 'queryBigMark');
    }

    /**
     * @return array
     */
    private function bigBenBody()
    {
        return [
            'receiverAddress' => [
                'province' => $this->data->getReceiver()->province,
                'city' => $this->data->getReceiver()->city,
                'district' => $this->data->getReceiver()->area,
                'address' => $this->data->getReceiver()->address,
            ],
            'senderAddress' => [
                'province' => $this->data->getSender()->province,
                'city' => $this->data->getReceiver()->city,
                'district' => $this->data->getReceiver()->area,
                'address' => $this->data->getReceiver()->address,
            ],
            'unionCode' => Uuid::uuid1()->toString()
        ];
    }

    /**
     * @return array
     */
    public function getBody()
    {
        $order = $this->data->getOrder();
        $sender = $this->data->getSender();
        $receiver = $this->data->getReceiver();
        $body = [
            'logisticsId' => $order->waybill,
            'orderId' => $order->code,
            'shipper' => $sender->name,
            'shipperProv' => $sender->province,
            'shipperCity' => $sender->city,
            'shipperDistrict' => $sender->area,
            'shipperAddress' => $sender->address,
            'shipperMobile' => $sender->mobile,
            'shipperTelephone' => '',
            'shipperCountry' => '中国',

            'consignee' => $receiver->name,
            'consigneeProv' => $receiver->province,
            'consigneeCity' => $receiver->city,
            'consigneeDistrict' => $receiver->area,
            'consigneeAddress' => $receiver->address,
            'consigneeMobile' => $receiver->mobile,
            'consigneeTelephone' => '',
            'consigneeCountry' => '中国',
            'idType' => 1,
            'customerId' => $order->extra['id_number'] ?? "",
            'shippingFee' => 0,
            'shippingFeeUnit' => '',
            'weight' => $this->data->getProductWeight(),
            'ieType' => 'I',
            'stockFlag' => 2,
            'customsCode' => $this->config->customsCode,
            'platformSource' => $this->config->platformSource,
            'sortContent' => '',
            'needBigMark' => 0,
            'netWeight' => $this->data->getProductWeight() - 0.01,
            'shipType' => '2',
            'warehouseCode' => $this->config->warehouseCode,
            'totalLogisticsNo' => '',
            'flightCode' => '',
            'userId' => '',
            'remark' => '',
            'intlOrderItemList' => [],
            'billEntity' => [
                'quantity' => 1,
                'ecpCode' => $order->extra['ecpCode'] ?? "",
                'ecpName' => $order->extra['ecpName'] ?? "",
                'ecpCodeG' => $order->extra['ecpCodeG'] ?? "",
                'ecpNameG' => $order->extra['ecpNameG'] ?? "",
                'wrapType' => 2,
                'companyCode' => $order->extra['companyCode'] ?? "",
            ],
        ];
        foreach ($this->data->getProducts() as $key => $product) {
            $body['intlOrderItemList'][$key] = [
                'itemId' => $key + 1,
                'itemName' => $product['name'],
                'itemUnitPrice' => $product->price,
                'itemQuantity' => $product->qty,
                'itemWeight' => $product->weight,
                'dutyMoney' => '0',
                'blInsure' => '0',
                'currencyType' => '142',
                'itemUnit' => '',
            ];
        }

        return $body;
    }
}