<?php

namespace LExpress\YTO;

use GuzzleHttp\Client;
use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\Response;
use Ramsey\Uuid\Uuid;

class Customs extends Base
{
    // 沙箱环境的地址
    public $sBoxUrl = 'http://customs.yto.net.cn/api/waybill/declare/test/1210/message';
    // 生产环境的地址
    public $prodUrl = 'http://customs.yto.net.cn/api/waybill/declare/imp/1210/message';

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
        $param = $this->encrypt(json_encode($this->getBody()), $this->config->clientSecret);

        $data = [
            'clientKey' => $this->config->clientKey,
            'channelCode' => $this->config->channelCode,
            'data' => $param,
            'timestamp' => time() . "000",
            'nonce' => Uuid::uuid6()->toString()
        ];

        $data['signature'] = $this->sign($data);


        $response = $this->post($this->getUrl(), $data);
        if ($response['status'] === 200) {
            return new Response(true, $response['message'], $response);
        }
        return new Response(false, $response['message']);
    }

    /**
     * @param string $url
     * @param array $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function post(string $url, array $params)
    {
        $client = new Client();
        $response = $client->post($url, [
            'json' => $params,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * @param string $json
     * @param string $aesKey
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function encrypt(string $json, string $aesKey)
    {
//        $text = $this->randomAlphanumeric(16);
//        $text .= $this->getNetworkBytesOrder(mb_strlen($json));
//        $text .= $json;
//        $text .= $this->pKCS7EncoderUitl(mb_strlen($text));
//
//        return $this->encryptAES($text, $aesKey);

        $response = $this->post("http://customs.yto.net.cn/api/aes/encrypt", [
            'msg' => $json,
            'aesKey' => $aesKey
        ]);
        return $response['data'];
    }

    /**
     * @param string $json
     * @param string $aesKey
     * @return string
     */
    protected function encryptAES(string $json, string $aesKey)
    {
        $aesKey = base64_decode($aesKey . "=");
        $iv = substr($aesKey, 0, 16);
        $data = openssl_encrypt($json, 'AES-128-CBC', $aesKey, OPENSSL_NO_PADDING, $iv);
        return base64_encode($data);
    }

    /**
     * @return array
     */
    public function getBody()
    {
        $extra = $this->data->getOrder()->extra['customs_extra'] ?? [];
        return [
            'appType' => $this->config->appType,
            'logisticsCode' => $this->config->logisticsCode,
            'logisticsName' => $this->config->logisticsName,
            'logisticsNo' => $this->data->getOrder()->waybill,
            'orderNo' => $this->data->getOrder()->code,
            'freight' => $this->data->getOrder()->extra['freight'] ?? 0,
            'insuredFee' => $this->data->getOrder()->extra['insuredFee'] ?? 0,
            'currency' => '142',
            'weight' => $this->data->getProductWeight(),
            'packNo' => 1,
            'goodsInfo' => mb_substr($this->data->getProductName(), 0, 90),
            'consignee' => $this->data->getReceiver()->name,
            'consigneeAddress' => $this->data->getReceiver()->getAllAddress(),
            'consigneeTelephone' => $this->data->getReceiver()->mobile,
            'extra' => $extra
        ];
    }

    /**
     * @param array $data
     * @return false|string
     */
    private function sign(array $data)
    {
        sort($data);
        return hash('sha1', implode('', $data));
    }
}