<?php
namespace LExpress\Zto;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $apiUrl = 'https://gjapi.zt-express.com/api/import/init';
    public $companyId = '';
    public $key = '';
    public $platformSource;
    public $warehouseCode;
}
