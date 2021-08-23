<?php


namespace LExpress\Zto;


use LExpress\ConfigInterFace;

class Push
{

    /**
     * @var ConfigInterFace
     * @author luffyzhao@vip.126.com
     */
    private $config;

    public function __construct(ConfigInterFace $config)
    {
        $this->config = $config;
    }

    /**
     * @param $logisticsNo
     * @param $orderNo
     * @return \LExpress\Response|void
     * @author luffyzhao@vip.126.com
     */
    public function handle($logisticsNo, $orderNo)
    {
        $dataJson = json_encode([
            'logisticsno' => $logisticsNo,
            'orderno' => $orderNo,
            'ordertype' => '1',
            'stockflag' => '2',
            'platformSource' => $this->config->platformSource,
        ]);

        $request = new Request($this->config);
        return $request->handle($dataJson, 'zto.intlbillorder.pushbill');
    }
}
