<?php

namespace LExpress\Yz;

use LExpress\Response;
use Ramsey\Uuid\Uuid;

/**
 * @property Config $config
 */
trait Request
{
    /**
     * @param array $data
     * @param string $apiCode
     * @return Response
     * @throws \Exception
     */
    private function request(array $data, string $apiCode = "oms_ordercreate_waybillno"): Response
    {
        $raw = [
            'apiCode' => $apiCode,
            'serialNo' => Uuid::uuid1()->toString(),
            'signature' => $this->sign($data),
            'msgType' => $this->config->msgType,
            'logistics_interface' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ];

        if(!empty($this->config->senderNo)){
            $raw['senderNo'] = $this->config->senderNo;
        }


        $json = $this->post($this->config->url, $raw, 5);
        $jsonArr = json_decode($json, true);


//        print_r(urldecode(http_build_query($raw)));

        if($jsonArr['code']== 'S00'){
            return new Response(true, $jsonArr['message'], $jsonArr, $jsonArr['body']['waybill_no']);
        }else{
            return new Response(false, $jsonArr['message'], $jsonArr);
        }
    }

    /**
     * 发送请求
     * user: wangjunjie
     * @param string $url
     * @param array $query
     * @param int $timeout
     * @return bool|string
     * @throws \Exception
     */
    public function post(string $url, array $query, int $timeout)
    {
        $headers = ["Content-Type:application/x-www-form-urlencoded; charset=UTF-8"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT , $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        // 是否报错
        if ($err = curl_errno($ch)) {
            throw new \Exception($err);
        }
        curl_close($ch);    // //关闭cURL资源，并且释放系统资源
        return $response;
    }

    /**
     * @param array $data
     * @param bool $rawOutput
     * @return string
     */
    private function sign(array $data, bool $rawOutput = false): string
    {
        return base64_encode($this->sha256(json_encode($data, JSON_UNESCAPED_UNICODE) . $this->config->secretKey, $rawOutput));
    }

    /**
     * @param $data
     * @param bool $rawOutput
     * @return false|string
     */
    private function sha256($data, bool $rawOutput = false)
    {
        if (!is_scalar($data)) {
            return false;
        }
        $data = (string)$data;
        $rawOutput = !!$rawOutput;
        return hash('sha256', $data, $rawOutput);
    }
}