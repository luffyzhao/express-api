<?php

namespace LExpress\YTO;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\Response;

class Create extends Base
{
    // 沙箱环境的地址
    protected $sBoxUrl = 'https://openuat.yto56test.com:6443/open/privacy_create_adapter/v1/Ef4kfE/K21000119';
    // 生产环境的地址
    protected $prodUrl = 'https://openapi.yto.net.cn:11443/open/privacy_create_adapter/v1/Ef4kfE/K73024429';

    /**
     * @param ConfigInterFace $config
     * @param Info $data
     */
    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function handle(): Response
    {
        $param = json_encode($this->getBody(), JSON_UNESCAPED_UNICODE);

        $data = [
            'timestamp' => time() . "000",
            'param' => $param,
            'format' => 'JSON'
        ];

        $sign= $param . "privacy_create_adapter" . $this->config->version . $this->config->secretKey;

        $data['sign'] = $this->encryptSignForOpen($sign);

        $response = $this->post($this->getUrl(), $data);
        if(!array_key_exists('mailNo', $response)){
            return new Response(false, $response['reason'] ?? ($response['apiErrorMsg'] ?? ""));
        }

        return new Response(true, '成功', $response, $response['mailNo']);
    }

    public function getBody()
    {
        $goods = [];
        foreach ($this->data->getProducts() as $product) {
            $goods[] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->qty,
            ];
        }
        return [
            'logisticsNo' => $this->data->getOrder()->code,
            'senderName' => $this->data->getSender()->name,
            'senderProvinceName' => $this->data->getSender()->province,
            'senderCityName' => $this->data->getSender()->city,
            'senderCountyName' => $this->data->getSender()->area,
            'senderAddress' => $this->data->getSender()->address,
            'senderMobile' => $this->data->getSender()->mobile,
            'recipientName' => $this->data->getReceiver()->name,
            'recipientProvinceName' => $this->data->getReceiver()->province,
            'recipientCityName' => $this->data->getReceiver()->city,
            'recipientCountyName' => $this->data->getReceiver()->area,
            'recipientAddress' => $this->data->getReceiver()->address,
            'recipientMobile' => $this->data->getReceiver()->mobile,
            'goods' => $goods,
            'weight' => $this->data->getProductWeight(),
            'realNameInfo' => [
                'name' => $this->data->getOrder()->extra['buyerName'],
                'cerNum' => $this->data->getOrder()->extra['buyerIdNumber'],
                'cerType' => "11",
            ]
        ];
    }
}