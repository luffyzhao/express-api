<?php

namespace LExpress\JD;

use LExpress\Response;

class Customs extends Base
{

    public function handle(): Response
    {
        $response = $this->request(
            'jingdong.eclp.order.addDeclareOrderCustoms',
            $this->getBody()
        );
        if ($this->config->format === 'json') {
            $respArr = json_decode($response, true);
        } else {
            $respArr = @simplexml_load_string($response);
        }
        if (null === $respArr) {
            return new Response(false, $response);
        }
        if (isset($respArr['error_response']) || $respArr['jingdong_eclp_order_addDeclareOrderCustoms_responce']['code'] !== '0') {
            return new Response(false, $respArr['error_response']['zh_desc'] ?? $respArr['jingdong_eclp_order_addDeclareOrderCustoms_responce']['declaredOrderCustoms_result']['message'], $respArr);
        } else {
            return new Response(true, '成功', $respArr);
        }
    }

    public function getBody()
    {
        return [
            'platformId' => $this->data->getOrder()->extra['platformId'],
            'platformName' => $this->data->getOrder()->extra['platformName'],
            'appType' => $this->config->appType,
            'logisticsNo' => $this->data->getOrder()->waybill,
            'billSerialNo' => $this->data->getOrder()->extra['billSerialNo'] ?? '',
            'billNo' => $this->data->getOrder()->extra['billNo'] ?? '',
            'freight' => $this->data->getOrder()->extra['freight'] ?? 0,
            'insuredFee' => $this->data->getOrder()->extra['insuredFee'] ?? 0,
            'netWeight' => $this->data->getProductWeight() - 0.01,
            'weight' => $this->data->getProductWeight(),
            'packNo' => 1,
            'worth' => $this->data->getProductPrice(),
            'goodsName' => $this->data->getProductName(),
            'orderNo' => $this->data->getOrder()->code,
            'shipper' => $this->data->getSender()->name,
            'shipperAddress' => $this->data->getSender()->getAllAddress(),
            'shipperTelephone' => $this->data->getSender()->mobile,
            'shipperCountry' => 142,
            'consigneeCountry' => 142,
            'consigneeProvince' => $this->data->getReceiver()->province,
            'consigneeCity' => $this->data->getReceiver()->city,
            'consingee' => $this->data->getReceiver()->name,
            'consigneeAddress' => $this->data->getReceiver()->getAllAddress(),
            'consigneeTelephone' => $this->data->getReceiver()->mobile,
            'buyerIdType' => 1,
            'buyerIdNumber' => $this->data->getOrder()->extra['buyerIdNumber'] ?? '身份证',
            'customsId' => $this->data->getOrder()->extra['customsId'] ?? '保税区编码（保税区在京东系统中的编码，由JD运营或者销售支持反馈给商家字段信息表中提供。）,长度不超过50',
            'customsCode' => $this->data->getOrder()->extra['customsCode'] ?? '海关关区编码（具体编码以海关官网的关区代码表为准）长度不超过20',
            'deptNo' => $this->data->getOrder()->extra['deptNo'] ?? '事业部编码,长度不超过50',
            'isvSource' => $this->data->getOrder()->extra['isvSource'] ?? '	ISV来源编号（JD运营或者销售支持反馈给商家字段信息表中提供，用于标识ISV软件服务商,京东内部事业部编号,可查）,否则拒单',
            'pattern' => $this->data->getOrder()->extra['pattern'] ?? 'beihuo',
            'isvUUID' => $this->data->getOrder()->code,
            'salesPlatformCreateTime' => $this->getCurrentTimeFormatted(),
            'platformType' => 2,
            'postType' => "I",
            'istax' => $this->data->getOrder()->extra['istax'] ?? 0,
            'logisticsCode' => $this->data->getOrder()->extra['logisticsCode'] ?? 'CYS0000010',
            'logisticsName' => $this->data->getOrder()->extra['logisticsName'] ?? '京东物流',
            'isDelivery' => $this->data->getOrder()->extra['isDelivery'] ?? 0,
        ];
    }
}