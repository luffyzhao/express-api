<?php


namespace LExpress\Zto;


use LExpress\Info;

class BigPen
{
    /**
     * @var Config
     * @author luffyzhao@vip.126.com
     */
    private $config;

    /**
     * BigPen constructor.
     * @param Config $config
     * @author luffyzhao@vip.126.com
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Info $info
     * @return \LExpress\Response|void
     * @author luffyzhao@vip.126.com
     */
    public function handle(Info $info)
    {

        $data = [
            'SenderProvince' => $info->getReceiver()->province,//发件省	   N
            'SenderCity' => $info->getReceiver()->city,//发件市	N
            'SenderDistrict' => $info->getReceiver()->area,//发件区(县/镇)	N
            'SenderAddress' => $info->getReceiver()->address,//发件详细地址	N
            'BuyerProvince' => $info->getSender()->province,//收件省	N
            'BuyerCity' => $info->getSender()->city,//收件市	N
            'BuyerDistrict' => $info->getSender()->area,//收件区(县/镇)	N
            'BuyerAddress' => $info->getSender()->address,//收件详细地址	N
        ];
        $jsonData = json_encode($data);

        $request = new Request($this->config);
        return $request->handle($jsonData, 'zto.intl.getdatoubi');
    }
}
