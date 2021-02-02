<?php

namespace LExpress;

class Client
{
    /**
     * @var ConfigInterFace
     * @author luffyzhao@vip.126.com
     */
    protected $config;

    /**
     * Client constructor.
     * @param ConfigInterFace $config
     * @author luffyzhao@vip.126.com
     */
    public function __construct(ConfigInterFace $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    public function create($data)
    {
        $create = $this->config->getNameSpace() . "\\Create";
        return new $create($this->config, $data);
    }
}
