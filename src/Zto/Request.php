<?php

namespace LExpress\Zto;

use GuzzleHttp\Client;
use LExpress\Response;

class Request
{
    /**
     * @var Config
     */
    private $config;

    private $timestamp;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->timestamp = time() . "000";
    }

    /**
     * @param array $content
     * @param $name
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(array $content, $name)
    {
        /** @var  $client */
        $client = new Client();
        $params = $this->getBody($content);

        $response = $client->request('POST', $this->getUrl($name), [
            'body' => $params
        ]);

        $result = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        if($result['success']){
            $result['data'] = $this->decrypt($result['data']);
            return new Response(0, '成功', $result);
        }

        return new Response(1, $result['error']['message'], $result);
    }

    /**
     * @param array $content
     * @return string
     */
    private function getBody(array $content)
    {
        $data = \GuzzleHttp\json_encode($content, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $body = [
            'data' => $data,
            'sign' => $this->getSign(\GuzzleHttp\json_encode($content, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))
        ];
        return $this->encrypt(\GuzzleHttp\json_encode($body));
    }

    /**
     * @param string $content
     * @return string
     */
    private function getSign(string $content): string
    {
        $sign = $this->timestamp . $this->config->secretKey . $content;
        return md5($sign);
    }

    /**
     * @param $name
     * @return string
     */
    private function getUrl($name)
    {
        return sprintf('%s?method=%s&timestamp=%s&appCode=%s', $this->config->url, $name, $this->timestamp, $this->config->appcode);
    }

    /**
     * @return string
     */
    private function iv()
    {
        $ivArr = [0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF];
        $iv = '';
        foreach ($ivArr as $value) {
            $iv .= chr($value);
        }
        return $iv;
    }

    /**
     * 加密消息体
     * @param $text
     * @return string
     */
    private function encrypt($text)
    {
        return base64_encode(openssl_encrypt($text, 'DES-CBC', $this->config->secretKey, OPENSSL_RAW_DATA, $this->iv()));
    }

    /**
     * @desc 解密
     * @param $data
     * @return string
     */
    private function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data), 'DES-CBC', $this->config->secretKey, OPENSSL_RAW_DATA, $this->iv());
    }
}