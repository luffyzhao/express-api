<?php


namespace LExpress;


use LExpress\Info\OrderInfo;
use LExpress\Info\ProductInfo;
use LExpress\Info\ReceiverInfo;
use LExpress\Info\SenderInfo;

class Info
{
    /** @var OrderInfo */
    protected $order;

    /** @var ReceiverInfo */
    protected $receiver;

    /** @var SenderInfo */
    protected $sender;

    /** @var ProductInfo[] */
    protected $products;

    /**
     * @return OrderInfo
     * @author luffyzhao@vip.126.com
     */
    public function getOrder(): OrderInfo
    {
        return $this->order;
    }

    /**
     * @param OrderInfo $order
     * @author luffyzhao@vip.126.com
     */
    public function setOrder(OrderInfo $order): void
    {
        $this->order = $order;
    }

    /**
     * @return ReceiverInfo
     * @author luffyzhao@vip.126.com
     */
    public function getReceiver(): ReceiverInfo
    {
        return $this->receiver;
    }

    /**
     * @param ReceiverInfo $receiver
     * @author luffyzhao@vip.126.com
     */
    public function setReceiver(ReceiverInfo $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * @return SenderInfo
     * @author luffyzhao@vip.126.com
     */
    public function getSender(): SenderInfo
    {
        return $this->sender;
    }

    /**
     * @param SenderInfo $sender
     * @author luffyzhao@vip.126.com
     */
    public function setSender(SenderInfo $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return ProductInfo[]
     * @author luffyzhao@vip.126.com
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param ProductInfo $info
     * @author luffyzhao@vip.126.com
     */
    public function addProduct(ProductInfo $info){
        $this->products[] = $info;
    }

    /**
     * @return float|int
     * @author luffyzhao@vip.126.com
     */
    public function getProductWeight(){
        $weight = 0;
        foreach ($this->products as $product){
            $weight += $product->getTotalWeight();
        }
        return $weight;
    }

    /**
     * @return float|int
     * @author luffyzhao@vip.126.com
     */
    public function getProductPrice(){
        $price = 0;
        foreach ($this->products as $product){
            $price += $product->getTotalPrice();
        }
        return $price;
    }

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    public function getProductName(){
        $name = "";
        foreach ($this->products as $product){
            $name .= $product->name . " ";
        }
        return $name;
    }

    /**
     * @param ProductInfo[] $products
     * @return Info
     * @author luffyzhao@vip.126.com
     */
    public function setProducts(array $products): Info
    {
        $this->products = $products;
        return $this;
    }
}
