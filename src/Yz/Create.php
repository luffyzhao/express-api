<?php

namespace LExpress\Yz;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

class Create implements OperateInterFace
{
    use Request;

    /**
     * @var Config
     */
    private $config;
    /**
     * @var Info
     */
    private $data;

    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function handle(): Response
    {
        return $this->request($this->getBody());
    }

    /**
     * @return array[]
     */
    public function getBody()
    {
        return [
            'OrderNormal' => [
                'base_product_no' => $this->config->baseProductNo,
                'created_time' => date('Y-m-d H:i:s'),
                'sender_no' => $this->config->senderNo,
                'ecommerce_user_id' => $this->config->ecommerceUserId,
                'logistics_order_no' => $this->data->getOrder()->code,
                'receiver' => $this->getReceiver(),
                'sender' => $this->getSender(),
                'cargos' => $this->getProducts(),
            ]
        ];
    }

    /**
     * 产品信息
     * @return array
     */
    private function getProducts()
    {
        $products = [];
        foreach ($this->data->getProducts() as $product) {
            $products[] = [
                'cargo_name' => $product->name,
                'cargo_weight' => $product->weight,
                'cargo_quantity' => $product->qty,
            ];
        }
        return $products;
    }

    /**
     * 发件人
     * @return array
     */
    private function getSender()
    {
        return [
            'name' => $this->data->getSender()->name,
            'post_code' => '',
            'phone' => '',
            'mobile' => $this->data->getSender()->mobile,
            'prov' => $this->data->getSender()->province,
            'city' => $this->data->getSender()->city,
            'county' => $this->data->getSender()->area,
            'address' => $this->data->getSender()->address,
        ];
    }

    /**
     * 收件
     * @return array
     */
    private function getReceiver()
    {
        return [
            'name' => $this->data->getReceiver()->name,
            'post_code' => '',
            'phone' => '',
            'mobile' => $this->data->getReceiver()->mobile,
            'prov' => $this->data->getReceiver()->province,
            'city' => $this->data->getReceiver()->city,
            'county' => $this->data->getReceiver()->area,
            'address' => $this->data->getReceiver()->address,
        ];
    }
}