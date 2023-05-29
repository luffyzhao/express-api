<?php

namespace LExpress\Sto;

use LExpress\ConfigInterFace;
use LExpress\Info;
use LExpress\OperateInterFace;
use LExpress\Response;

class Customs implements OperateInterFace
{

    /**
     * @var ConfigInterFace
     */
    private $config;
    /**
     * @var Info
     */
    private $data;

    public function __construct(ConfigInterFace $config, Info $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function handle(): Response
    {
        return new Response(true);
    }

    public function getBody()
    {
        return [];
    }
}