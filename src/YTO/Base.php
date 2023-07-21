<?php

namespace LExpress\YTO;

use GuzzleHttp\Client;
use LExpress\Info;
use LExpress\OperateInterFace;

abstract class Base implements OperateInterFace
{
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Info
     */
    protected $data;

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return $this->config->isSBox ? $this->sBoxUrl : $this->prodUrl;
    }

    protected function encryptSignForOpen($sign)
    {
        return base64_encode(pack("H*", md5($sign)));
    }

    protected function post(string $url, array $params)
    {
        $client = new Client();
        $response = $client->post($url, [
            'form_params' => $params,
            'verify' => false,
            'http_errors' => false,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param int $count
     * @return string
     */
    protected function pKCS7EncoderUitl(int $count)
    {
        $amount = 32 - ($count % 32);
        if ($amount === 0) {
            $amount = 32;
        }
        $padChr = chr($amount);
        $tmp = '';
        for ($index = 0; $index < $amount; $index++) {
            $tmp .= $padChr;
        }
        return $tmp;
    }

    /**
     * @param $string
     * @return array
     */
    function getBytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {    //遍历每一个字符 用ord函数把它们拼接成一个php数组
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }

    /**
     * @param $number
     * @return string
     */
    protected function getNetworkBytesOrder($number)
    {
        $order = [];
        $order[3] = chr($number & 0xFF);
        $order[2] = chr($number >> 8 & 0xFF);
        $order[1] = chr($number >> 16 & 0xFF);
        $order[0] = chr($number >> 32 & 0xFF);
        ksort($order);
        return implode('', $order);
    }

    protected function randomAlphanumeric(int $count)
    {
        $char = '';
        for ($index = 0; $index < $count; $index++) {
            $char .= chr(rand(100, 122));
        }
        return $char;
    }
}