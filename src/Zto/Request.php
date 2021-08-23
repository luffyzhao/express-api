<?php


namespace LExpress\Zto;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LExpress\Response;
use Throwable;

class Request
{
    /**
     * @var Config
     * @author luffyzhao@vip.126.com
     */
    private $config;

    /**
     * Request constructor.
     * @param Config $config
     * @author luffyzhao@vip.126.com
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $content
     * @param $name
     * @return Response|void
     * @author luffyzhao@vip.126.com
     */
    public function handle($content, $name)
    {
        /** @var  $client */
        $client = new Client();

        try {
            $params = $this->getBody($content, $name);

            $response = $client->post($this->config->apiUrl, [
                'form_params' => $params
            ]);

            try {
                $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
            } catch (GuzzleException $e) {
                throw new Exception($response->getBody()->getContents());
            }

            if ($response->getStatusCode() === 200 && $body['status'] === true) {
                return new Response(1, '成功', $body);
            } else {
                return new Response(0, '失败', $body);
            }
        } catch (Throwable | Exception $e) {
            return new Response(0, '请求失败:' . $e->getMessage());
        }
    }

    /**
     * @param $content
     * @param string $name
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getBody(string $content, string $name)
    {
        return $sendData = [
            'data' => $content,
            'msg_type' => $name,
            'data_digest' => $this->getSign($content),
            'company_id' => $this->config->companyId
        ];
    }

    /**
     * Note: 生成签名数据
     * User: Yao
     * Date: 2018/12/10
     * Time: 14:19
     * @param $data
     * @return string
     */
    protected function getSign($data)
    {
        return base64_encode(pack('H*', md5($data . $this->config->key)));
    }
}
