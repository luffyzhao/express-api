<?php

namespace LExpress\Zto;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

/**
 * 推海关
 */
class Customs implements OperateInterFace
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Info
     */
    private $data;

    /**
     * @param ConfigInterFace $config
     * @param Info $data
     */
    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function handle(): Response
    {
        $request = new Request($this->config);
        return $request->handle($this->getBody(), 'queryBideclareCustomsWaybillgMark');
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return [
            'billCode' => $this->data->getOrder()->waybill,
            'customerNo' => $this->data->getOrder()->code,
            'customerCode' => $this->config->platformSource
        ];
    }
}