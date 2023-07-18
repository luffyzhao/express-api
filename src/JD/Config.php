<?php

namespace LExpress\JD;

use LExpress\ConfigInterFace;

class Config implements ConfigInterFace
{
    public $serverUrl = 'https://api.jd.com/routerjson';

    public $accessToken;

    public $connectTimeout = 0;

    public $readTimeout = 0;

    public $appKey;

    public $appSecret;

    public $version = '2.0';

    public $format = 'json';

    private $charset_utf8 = 'UTF-8';

    private $json_param_key = '360buy_param_json';

}