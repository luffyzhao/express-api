<?php
namespace LExpress\Zto;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $apiUrl = 'https://japi-test.zto.com/';
    public $companyId = '';
    public $key = '';
    public $platformSource;
    public $warehouseCode = '';
}
