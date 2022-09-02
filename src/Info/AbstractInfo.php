<?php

namespace LExpress\Info;

abstract class AbstractInfo implements \ArrayAccess
{
    /** @var array 额外参数 */
    public $extra = [];

    /**
     * @param $name
     * @return void
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return $this->extra[$name] ?? null;
    }

    /**
     * @param $offset
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getMethod($offset)
    {
        return 'get' . $this->camelize($offset);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        } else {
            $this->extra[$name] = $value;
        }
    }

    /**
     * @param string $words
     * @param string $separator
     * @return string
     */
    function camelize($words, $separator = '_')
    {
        $words = $separator . str_replace($separator, " ", strtolower($words));
        return ltrim(str_replace(" ", "", ucwords($words)), $separator);
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset) || method_exists($this, $this->getMethod($offset));
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            if (method_exists($this, $this->getMethod($offset))) {
                return $this->{$this->getMethod($offset)}($offset);
            }
            return $this->$offset;
        }
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->{$offset} = $value;
        }
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}