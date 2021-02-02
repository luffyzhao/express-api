<?php


namespace LExpress;


use LExpress\Sto\Config;

interface OperateInterFace
{
    public function __construct(Config $config, Info $data);
    public function handle() : Response;
}
