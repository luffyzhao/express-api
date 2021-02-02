<?php


namespace LExpress;


use Psr\Http\Message\StreamInterface;

class Response
{
    /**
     * @var int
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

    public function __construct(int $status, string $message, array $data = [])
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * @return int
     * @author luffyzhao@vip.126.com
     */
    public function getStatus(): int
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
}
