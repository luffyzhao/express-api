<?php

namespace LExpress\Sf;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\Info\ProductInfo;
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
     */
    public function handle(): Response
    {
        try {

            $request = new Request($this->config);
            return $request->handle($this->getBody());

        } catch (\Exception|\Throwable $exception) {
            return new Response(0, $exception->getMessage(), []);
        }
    }


    /**
     * @param $msgData
     * @param $timestamp
     * @return string
     */
    private function msgDigest($msgData, $timestamp): string
    {
        return base64_encode(md5((urlencode($msgData . $timestamp . $this->config->checkword)), TRUE));
    }

    /**
     * @return array
     */
    private function getMsgData(): array
    {
        $data = [
            'cargoDetails' => $this->getProducts(),
            'contactInfoList' => [[
                'address' => $this->data->getSender()->address,
                'city' => $this->data->getSender()->city,
                'contact' => $this->data->getSender()->name,
                'contactType' => 1,
                'county' => $this->data->getSender()->area,
                'province' => $this->data->getSender()->province,
                'mobile' => $this->data->getSender()->mobile
            ], [
                'address' => $this->data->getReceiver()->address,
                'city' => $this->data->getReceiver()->city,
                'contact' => $this->data->getReceiver()->name,
                'contactType' => 2,
                'county' => $this->data->getReceiver()->area,//
                'province' => $this->data->getReceiver()->province,
                'mobile' => $this->data->getReceiver()->mobile
            ]],
            'expressTypeId' => $this->config->expressTypeId,
            'isGenWaybillNo' => $this->config->isGenWaybillNo,
            'language' => 'zh-CN',
            'monthlyCard' => $this->config->monthlyCard,
            'orderId' => $this->data->getOrder()->code
        ];
        if($this->data->getOrder()->waybill){
            $data['waybillNoInfoList'] = [[
                'waybillNo' => $this->data->getOrder()->waybill,
                'waybillType' => 1
            ]];
        }
        return $data;
    }

    /**
     * @return array
     */
    private function getProducts(): array
    {
        $products = [];
        foreach ($this->data->getProducts() as $product) {
            $products[] = [
                'amount' => $product->price,
                'count' => $product->qty,
                'name' => $product->name,
                'unit' => "个",
                'currency' => "CNY",
                'weight' => $product->weight,
            ];
        }
        return $products;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        $timestamp = time();
        $msgData = \GuzzleHttp\json_encode($this->getMsgData(), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        return [
            'partnerID' => $this->config->partnerID,
            'requestID' => Uuid::uuid1()->toString(),
            'serviceCode' => 'EXP_RECE_CREATE_ORDER',
            'timestamp' => $timestamp,
            'msgDigest' => $this->msgDigest($msgData, $timestamp),
            'msgData' => $msgData
        ];
    }
}