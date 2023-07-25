<?php

namespace LExpress\Sf;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;
use Ramsey\Uuid\Uuid;

class GetPdf implements OperateInterFace
{

    /**
     * @var ConfigInterFace
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
        try {

            $request = new Request($this->config);
            return $request->handle($this->getBody());

        } catch (\Exception|\Throwable $exception) {
            return new Response(0, $exception->getMessage(), []);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getBody()
    {
        $timestamp = time();
        $msgData = \GuzzleHttp\json_encode($this->getMsgData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return [
            'partnerID' => $this->config->partnerID,
            'requestID' => Uuid::uuid6()->toString(),
            'serviceCode' => 'COM_RECE_CLOUD_PRINT_WAYBILLS',
            'timestamp' => $timestamp,
            'msgDigest' => $this->msgDigest($msgData, $timestamp),
            'msgData' => $msgData
        ];
    }

    private function getMsgData()
    {
        return [
            'templateCode' => 'fm_76130_standard_BLGYLdx1Fguw',
            'version' => '',
            'sync' => true,
            'customTemplateCode' => 'fm_76130_standard_custom_10008111409_1',
            'documents' => [
                [
                    'masterWaybillNo' => $this->data->getOrder()->waybill,
                    'waybillNoCheckType' => '1',
                    'waybillNoCheckValue' => $this->data->getOrder()->extra['waybillNoCheckValue'],
                ]
            ]
        ];
    }

    private function msgDigest(string $msgData, int $timestamp)
    {
        return base64_encode(md5((urlencode($msgData . $timestamp . $this->config->checkword)), TRUE));
    }
}