<?php


namespace LExpress\Sto;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\json_encode as json_encodeAlias;
use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;
use Ramsey\Uuid\Uuid;

class Create implements OperateInterFace
{
    /**
     * @var ConfigInterFace
     * @author luffyzhao@vip.126.com
     */
    private $config;
    /**
     * @var Info
     * @author luffyzhao@vip.126.com
     */
    private $data;

    /**
     * Create constructor.
     * @param Config $config
     * @param Info $data
     * @author luffyzhao@vip.126.com
     */
    public function __construct(Config $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }


    /**
     * @author luffyzhao@vip.126.com
     */
    public function handle(): Response
    {
        /** @var  $client */
        $client = new Client();
        try {
            $params = $this->getBody(
                $this->getContent(),
                'OMS_EXPRESS_ORDER_CREATE'
            );

            $response = $client->post($this->config->apiUrl, [
                'form_params' => $params
            ]);

            $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200 && $body['success'] === 'true') {
                return new Response(1, '成功', $body['data']);
            } else {
                return new Response(0, '失败', $body['data']);
            }
        } catch (GuzzleException $e) {
            return new Response(0, '接口出错，请联系管理员;');
        }
    }


    /**
     * @param $content
     * @param string $name
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getBody($content, $name = 'OMS_EXPRESS_ORDER_CREATE')
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

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getContent()
    {
        return json_encodeAlias([
            'orderNo' => $this->data->getOrder()->code,
            'orderSource' => $this->config->orderSource,
            'billType' => $this->config->billType,
            'orderType' => $this->config->orderType,
            'sender' => [
                'name' => $this->data->getSender()->name,
                'mobile' => $this->data->getSender()->mobile,
                'province' => $this->data->getSender()->province,
                'city' => $this->data->getSender()->city,
                'area' => $this->data->getSender()->area,
                'address' => $this->data->getSender()->address,
            ],
            'receiver' => [
                'name' => $this->data->getReceiver()->name,
                'mobile' => $this->data->getReceiver()->mobile,
                'province' => $this->data->getReceiver()->province,
                'city' => $this->data->getReceiver()->city,
                'area' => $this->data->getReceiver()->area,
                'address' => $this->data->getReceiver()->address,
            ],
            'cargo' => [
                'battery' => '10',
                'goodsType' => '小件',
                'goodsName' => $this->data->getProductName(),
                'goodsCount' => 1,
                'weight' => $this->data->getProductWeight(),
                'goodsAmount' => $this->data->getProductPrice()
            ],
            "customer" => $this->config->customer,
            'internationalAnnex' => $this->config->internationalAnnex,
            'payModel' => $this->config->payModel,
            // 报关数据
            "extendFieldMap" =>[
                "otherInfo"=>[
                    'appType' => '1',
                    'appStatus' => '2',
                    'freight' => '0',
                    'billNo' => '',
                    'insuredFee' => '0',
                    'packNo' => '1',
                    'goodsInfo' =>  $this->data->getProductName(),
                    'currency' => '142',
                    'note' => '',
                ]
            ]
        ]);
    }
}
