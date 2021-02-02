<?php


namespace LExpress\Info;


class ProductInfo implements \ArrayAccess
{
    public $id;
    /** @var string 商品名称 */
    public $name;
    /** @var float 单价 */
    public $price;
    /** @var int 数量 */
    public $qty;
    /** @var float 毛重 */
    public $weight;

    /**
     * @return float|int
     * @author luffyzhao@vip.126.com
     */
    protected function getTotalWeight(){
        return $this->weight * $this->qty;
    }

    /**
     * @return float|int
     * @author luffyzhao@vip.126.com
     */
    protected function getTotalPrice(){
        return $this->price * $this->qty;
    }

    /**
     * @param $offset
     * @return string
     * @author luffyzhao@vip.126.com
     */
    protected function getMethod($offset){
        return 'get' . ucfirst($offset);
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset) || method_exists($this, $this->getMethod($offset));
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)){
            if(method_exists($this, $this->getMethod($offset))){
                return $this->{$this->getMethod($offset)}($offset);
            }
            return $this->$offset;
        }
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {

    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {

    }
}
