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
     * @param Info $info
     * @return Response
     * @throws \Exception
     * @author luffyzhao@vip.126.com
     */
    public function create(Info $info)
    {
        $create = str_replace('Config', 'Create', get_class($this->config));
        if(class_exists($create)){
            $object =  new $create($this->config, $info);
            if($object instanceof OperateInterFace){
                return $object->handle();
            }
        }
        throw new \Exception('没有创建接口！');
    }

    /**
     * @param $info
     * @return mixed
     * @throws \Exception
     */
    public function getBodyInfo ($info){
        $create = str_replace('Config', 'Create', get_class($this->config));
        if(class_exists($create)){
            $object =  new $create($this->config, $info);
            if($object instanceof OperateInterFace){
                return $object->getBody();
            }
        }
        throw new \Exception('没有创建接口！');
    }

    /**
     * @param Info $info
     * @return Response
     * @throws \Exception
     */
    public function customs(Info $info){
        $create = str_replace('Config', 'Customs', get_class($this->config));
        if(class_exists($create)){
            $object =  new $create($this->config, $info);
            if($object instanceof OperateInterFace){
                return $object->handle();
            }
        }
        throw new \Exception('没有海关申报接口！');
    }
}
