<?php

namespace LExpress;

class AbstractConfig implements ConfigInterFace
{
    /** @var array 额外参数 */
    public $extra = [];
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }else{
                $this->extra[$key] = $value;
            }
        }
    }
}