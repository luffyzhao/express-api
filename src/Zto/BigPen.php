<?php


namespace LExpress\Zto;


use LExpress\Info;
use Ramsey\Uuid\Uuid;

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
            'unionCode' => Uuid::uuid1()->toString(), //唯一标示	   N
            'send_province' => $info->getReceiver()->province,//发件省	   N
            'send_city' => $info->getReceiver()->city,//发件市	N
            'send_district' => $info->getReceiver()->area,//发件区(县/镇)	N
            'send_address' => $info->getReceiver()->address,//发件详细地址	N
            'receive_district' => $info->getSender()->province,//收件省	N
            'receive_city' => $info->getSender()->city,//收件市	N
            'receive_province' => $info->getSender()->area,//收件区(县/镇)	N
            'receive_address' => $info->getSender()->address,//收件详细地址	N
        ];
        $jsonData = json_encode($data);

        $request = new Request($this->config);
        return $request->handle($jsonData, 'zto.innovate.bagAddrMark');
    }
}
