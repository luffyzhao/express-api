<?php


namespace LExpress;


use Psr\Http\Message\StreamInterface;

class Response
{
    /**
     * @var bool
     * @author luffyzhao@vip.126.com
     */
    private $status;
    /**
     * @var array
     * @author luffyzhao@vip.126.com
     */
    private $data;
    /**
     * @var string
     * @author luffyzhao@vip.126.com
     */
    private $message;

    /**
     * @var string
     */
    private $code;

    public function __construct(bool $status, string $message = '', array $data = [], string $code = '')
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return bool
     * @author luffyzhao@vip.126.com
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return array
     * @author luffyzhao@vip.126.com
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return string
     * @author luffyzhao@vip.126.com
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return void
     */
    public function addData(array $data){
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param mixed $code
     */
    public function setCode(?string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
