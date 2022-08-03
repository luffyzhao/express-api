<?php


namespace LExpress;



interface OperateInterFace
{
    public function __construct(ConfigInterFace $config, Info $data);
    public function handle() : Response;

    public function getBody();
}
