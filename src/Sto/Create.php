<?php


namespace LExpress\Sto;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\json_encode as json_encodeAlias;
use LExpress\ConfigInterFace;
use LExpress\Response;

class Create
{
    /**
     * @var ConfigInterFace
     * @author luffyzhao@vip.126.com
     */
    private $config;
    /**
     * @var array
     * @author luffyzhao@vip.126.com
     */
    private $data;

    /**
     * Create constructor.
     * @param Config $config
     * @param array $data
     * @author luffyzhao@vip.126.com
     */
    public function __construct(Config $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }


    /**
     * @author luffyzhao@vip.126.com
     */
    public function handle()
    {
        $client = new Client();
        try {
            $response = $client->post($this->config->apiUrl, [
                'form_params' => $this->getBody()
            ]);
            $body = \GuzzleHttp\json_decode($response->getBody()->getContents());
            if ($response->getStatusCode() === 200 && $body['success'] === true) {
                return new Response(1, '成功', $response->getBody());
            } else {
                return new Response(1, '失败', $response->getBody());
            }
        } catch (GuzzleException $e) {
            return new Response(1, '失败');
        }
    }

    /**
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getBody()
    {
        return [
            'content' => $this->getContent(),
            'data_digest' => $this->getSign(),
            'api_name' => 'OMS_EXPRESS_ORDER_CREATE',
            'from_appkey' => $this->config->fromAppKey,
            'from_code' => $this->config->fromCode,
            'to_appkey' => $this->config->toAppKey,
            'to_code' => $this->config->toCode,
        ];
    }

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getSign()
    {
        return base64_encode(md5($this->getContent() . $this->config->secretKey, true));
    }

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getContent()
    {
        return json_encodeAlias($this->data);
    }
}
