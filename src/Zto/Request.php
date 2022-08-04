<?php


namespace LExpress\Zto;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LExpress\Response;
use LExpress\Zto\SDK\ZopClient;
use LExpress\Zto\SDK\ZopProperties;
use LExpress\Zto\SDK\ZopRequest;
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
        try {
            $properties = new ZopProperties("kfpttestCode", "kfpttestkey==");
            $client = new ZopClient($properties);
            $request = new ZopRequest();
            $request->setUrl($this->config->apiUrl . $name);
            $request->setBody($content);
            try {
                $response = \GuzzleHttp\json_decode($client->execute($request));
            }catch (Exception $exception){

            }



        }catch (Exception $exception){

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
