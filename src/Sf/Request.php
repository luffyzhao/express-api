<?php

namespace LExpress\Sf;

use GuzzleHttp\Client;
use LExpress\Response;

class Request
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $content
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(array $content)
    {
        /** @var  $client */
        $client = new Client();
        $response = $client->request('POST', $this->config->getUrl(), [
            'form_params' => $content,
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8'
            ]
        ]);
        $result = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        if ($result['apiResultCode'] === 'A1000') {
            $resultContData = \GuzzleHttp\json_decode($result['apiResultData'], true);
            if($resultContData['success'] === true ){
                return new Response(true, '成功', $result, $resultContData['msgData']['waybillNoInfoList'][0]['waybillNo']);
            }else{
                return new Response(false, $result['apiErrorMsg'], $resultContData);
            }
        } else {
            return new Response(false, $result['apiErrorMsg'], $result);
        }
    }
}