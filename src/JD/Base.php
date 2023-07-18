<?php

namespace LExpress\JD;

use DateTimeZone;
use Exception;
use LExpress\ConfigInterFace;
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
     * @param ConfigInterFace $config
     * @param Info $data
     */
    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * @return string
     */
    protected function getCurrentTimeFormatted()
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * @param $params
     * @return string
     */
    protected function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->config->appSecret;
        foreach ($params as $k => $v) {
            if ("@" != substr($v, 0, 1)) {  //判断是不是文件上传
                $stringToBeSigned .= "$k$v";
            }
        }
        $stringToBeSigned .= $this->config->appSecret;
        return strtoupper(md5($stringToBeSigned));
    }

    /**
     * @param $method
     * @return array
     */
    protected function getCommonParams($method)
    {
        return [
            'method' => $method,
            'access_token' => $this->config->accessToken,
            'app_key' => $this->config->appKey,
            'timestamp' => $this->getCurrentTimeFormatted(),
            'format' => $this->config->format,
            'v' => $this->config->version,
        ];
    }

    /**
     * @param $method
     * @param array $apiParams
     * @return string
     * @throws Exception
     */
    protected function request($method, array $apiParams): string
    {
        ksort($apiParams);
        $params = [
            'method' => $method,
            'app_key' => $this->config->appKey,
            'timestamp' => $this->getCurrentTimeFormatted(),
            'v' => $this->config->version,
            '360buy_param_json' => json_encode($apiParams)
        ];
        if (!empty($this->config->accessToken)) {
            $params['access_token'] = $this->config->accessToken;
        }
        $params['sign'] = $this->generateSign($params);
        $url = $this->config->serverUrl . "?";
        foreach ($params as $sysParamKey => $sysParamValue)
        {
            $url .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }
        return $this->curl($url, []);
    }

    /**
     * @param $url
     * @param ?array $postFields
     * @return bool|string
     * @throws Exception
     */
    protected function curl($url, array $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->config->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->readTimeout);
        }
        if ($this->config->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->config->connectTimeout);
        }

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            $postMultipart = false;
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                } else {
                    $postMultipart = true;
                }
            }
            echo "post:" .$postBodyString . "\n";
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }
        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }
}