<?php

namespace LExpress\JD;

use DateTimeZone;
use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

class Create extends Base
{
    public function handle(): Response
    {
        $response = $this->request(
            'jingdong.ldop.waybill.receive',
            $this->getBody()
        );
        if ($this->config->format === 'json') {
            $respArr = json_decode($response, true);
        } else {
            $respArr = @simplexml_load_string($response);
        }
        if (is_null($respArr)) {
            return new Response(false, $response);
        }

        if (isset($respArr['error_response']) || $respArr['jingdong_ldop_waybill_receive_responce']['receiveorderinfo_result']['resultCode'] !== 100) {
            return new Response(false, $respArr['error_response']['zh_desc'] ?? $respArr['jingdong_ldop_waybill_receive_responce']['receiveorderinfo_result']['resultMessage'], $respArr);
        } else {
            return new Response(true, '成功', $respArr, $respArr['jingdong_ldop_waybill_receive_responce']['receiveorderinfo_result']['deliveryId']);
        }
    }

    public function getBody(): array
    {

        return [
            'salePlat' => $this->data->getOrder()->extra['salePlat'] ?? '0030001',
            'customerCode' => $this->data->getOrder()->extra['customerCode'] ?? '010K96932',
            'orderId' => $this->data->getOrder()->code,
            'thrOrderId' => $this->data->getOrder()->code,

            'senderName' => $this->data->getSender()->name,
            'senderAddress' => $this->data->getSender()->getAllAddress(),
            'senderMobile' => $this->data->getSender()->mobile,


            'receiveName' => $this->data->getReceiver()->name,
            'receiveAddress' => $this->data->getReceiver()->getAllAddress(),
            'province' => $this->data->getReceiver()->province,
            'city' => $this->data->getReceiver()->city,
            'county' => $this->data->getReceiver()->area,
            'receiveMobile' => $this->data->getReceiver()->mobile,
            'packageCount' => $this->data->getOrder()->extra['packageCount'] ?? 1,
            'weight' => $this->data->getProductWeight(),
            'vloumn' => 1,
        ];
    }
}