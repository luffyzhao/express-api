<?php


namespace LExpress\Sto;


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

            if ($response->getStatusCode() === 200 && $body['success'] === 'true') {
                return new Response(1, '成功', $body['data']);
            } else {
                return new Response(0, '失败', $body['data']);
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
        return [
            'content' => $content,
            'data_digest' => $this->getSign($content),
            'api_name' => $name,
            'from_appkey' => $this->config->fromAppKey,
            'from_code' => $this->config->fromCode,
            'to_appkey' => $this->config->toAppKey,
            'to_code' => $this->config->toCode,
        ];
    }

    /**
     * @param $string
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getSign($string)
    {
        return base64_encode(md5($string . $this->config->secretKey, true));
    }
}
