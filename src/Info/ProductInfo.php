<?php


namespace LExpress\Info;


class ProductInfo extends AbstractInfo
{
    /** @var string 商品名称 */
    public $name;
    /** @var float 单价 */
    public $price;
    /** @var int 数量 */
    public $qty;
    /** @var float 毛重 */
    public $weight;

    public $country = "美国";

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
}
