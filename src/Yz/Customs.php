<?php

namespace LExpress\Yz;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;
use Ramsey\Uuid\Uuid;

class Customs implements OperateInterFace
{
    use Request;

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
     * @throws \Exception
     */
    public function handle(): Response
    {
        $response = $this->post($this->config->customsUrl, $this->getBody(), 10);
//        print_r($this->getBody());
//        print_r($response);
        return new Response(true);
    }

    /**
     * @return array
     */
    public function getBody()
    {
        $xml = $this->getCeb511($this->data->getOrder()->waybill);
        return [
            'Request' => base64_encode($xml),
            'Signed' => md5(base64_encode($xml) . $this->data->getOrder()->extra['Key']),
            'Action' => 'CEB511',
            'Dcode' => $this->data->getOrder()->extra['Dcode'],
        ];
    }

    /**
     * @param $logisticsNo
     * @return string
     */
    private function getCeb511($logisticsNo)
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
            "<ceb:CEB511Message guid=\"" . Uuid::uuid6()->toString() . "\" version=\"1.0\"  xmlns:ceb=\"http://www.chinaport.gov.cn/ceb\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">" .
            "<ceb:Logistics><ceb:LogisticsHead><ceb:guid>" . Uuid::uuid6()->toString() . "</ceb:guid><ceb:appType>1</ceb:appType>" .
            "<ceb:appTime>" . date('YmdHis') . "</ceb:appTime><ceb:appStatus>2</ceb:appStatus>" .
            "<ceb:logisticsCode>4301980029</ceb:logisticsCode><ceb:logisticsName>长沙一邮速递服务有限公司</ceb:logisticsName>" .
            "<ceb:logisticsNo>" . $logisticsNo . "</ceb:logisticsNo><ceb:billNo>CSBLGYL" . date('YmdH') . "</ceb:billNo><ceb:orderNo>" . $this->data->getOrder()->code . "</ceb:orderNo>" .
            "<ceb:freight>0</ceb:freight><ceb:insuredFee>0</ceb:insuredFee><ceb:currency>142</ceb:currency><ceb:weight>" . $this->data->getProductWeight() . "</ceb:weight>" .
            "<ceb:packNo>1</ceb:packNo><ceb:goodsInfo>" . $this->data->getProductName() . "</ceb:goodsInfo><ceb:consignee>" . $this->data->getReceiver()->name . "</ceb:consignee>" .
            "<ceb:consigneeAddress>" . $this->data->getReceiver()->province . $this->data->getReceiver()->city . $this->data->getReceiver()->area . $this->data->getReceiver()->address . "</ceb:consigneeAddress>" .
            "<ceb:consigneeTelephone>" . $this->data->getReceiver()->mobile . "</ceb:consigneeTelephone><ceb:note>" . $this->data->getOrder()->extra['ceb511_note'] . "</ceb:note>" .
            "</ceb:LogisticsHead></ceb:Logistics>" .
            "<ceb:BaseTransfer>" .
            "<ceb:copCode>4301980029</ceb:copCode>" .
            "<ceb:copName>长沙一邮速递服务有限公司</ceb:copName>" .
            "<ceb:dxpMode>DXP</ceb:dxpMode>" .
            "<ceb:dxpId>DXPENT0000021162</ceb:dxpId>" .
            "<ceb:note>1210</ceb:note>" .
            "<ceb:ebcCode>" . $this->data->getOrder()->extra['areaCode'] . "</ceb:ebcCode>" .
            "<ceb:ebcName>" . $this->data->getOrder()->extra['areaName'] . "</ceb:ebcName>" .
            "<ceb:trafMode>6</ceb:trafMode>" .
            "<ceb:trafNo>1210</ceb:trafNo>" .
            "<ceb:voyageNo>1210</ceb:voyageNo>" .
            "<ceb:RECEIVERDEPARTMENT>C</ceb:RECEIVERDEPARTMENT>" .
            "<ceb:V_CAR_IO_KEY>CSBLGYL" . date('YmdH') . "</ceb:V_CAR_IO_KEY>" .
            "<ceb:N_CAR_IO_KEY_COUNT>1</ceb:N_CAR_IO_KEY_COUNT>" .
            "</ceb:BaseTransfer></ceb:CEB511Message>";

    }
}