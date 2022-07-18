<?php

namespace LExpress\Sf;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\Info\ProductInfo;
use LExpress\OperateInterFace;
use LExpress\Response;

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
            $timestamp = time();
            $msgData = \GuzzleHttp\json_encode($this->getMsgData());
            $postData = [
                'partnerID' => $this->config->partnerID,
                'requestID' => $this->createUuid(),
                'serviceCode' => 'EXP_RECE_CREATE_ORDER',
                'timestamp' => $timestamp,
                'msgDigest' => $this->msgDigest($msgData, $timestamp),
                'msgData' => $msgData
            ];

            $resultCont = $this->sendPost($this->config->getUrl(), $postData);

            if ($resultCont['apiResultCode'] === 'A1000') {
                $resultContData = \GuzzleHttp\json_decode($resultCont['apiResultData'], true);
                if($resultContData['success'] === true){
                    return new Response(1, '成功', $resultContData);
                }else{
                    return new Response(0, $resultCont['apiErrorMsg'], $resultContData);
                }
            } else {
                return new Response(0, $resultCont['apiErrorMsg'], $resultCont);
            }
        } catch (\Exception|\Throwable $exception) {
            return new Response(0, $exception->getMessage(), []);
        }
    }

    /**
     * @return string
     */
    private function createUuid(): string
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
        return $uuid;
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
     * @param $url
     * @param $post_data
     * @return false|string
     */
    private function sendPost($url, $post_data)
    {

        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded;charset=utf-8',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);

        $result = \GuzzleHttp\json_decode(file_get_contents($url, false, $context), true);

        return $result;
    }

    /**
     * @return array
     */
    private function getMsgData(): array
    {
        return [
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
                'county' => $this->data->getReceiver()->area,
                'province' => $this->data->getReceiver()->province,
                'mobile' => $this->data->getReceiver()->mobile
            ]],
            'customsInfo' => [
                'declaredValue' => $this->data->getProductPrice(),
                'declaredValueCurrency' => 'CNY',
            ],
            'expressTypeId' => '',
            'extraInfoList' => '',
            'isOneselfPickup' => '',
            'language' => 'zh-CN',
            'monthlyCard' => $this->config->monthlyCard,
            'orderId' => $this->data->getOrder()->code,
            'parcelQty' => 1,
            'payMethod' => 1,
            'totalWeight' => $this->data->getProductWeight(),
        ];
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
                'volume' => 0.0,
                'weight' => $product->weight,
            ];
        }
        return $products;
    }
}