<?php


namespace LExpress\Info;


class SenderInfo extends AbstractInfo
{
    /** @var string 姓名 */
    public $name = '';
    /** @var string 手机号码 */
    public $mobile = '';
    /** @var string 县 */
    public $province = '';
    /** @var string 市 */
    public $city = '';
    /** @var string 县区 */
    public $area = '';
    /** @var string 详细地址 */
    public $address = '';

    public function getAllAddress(){
        return $this->province . " " . $this->city . " " . $this->area . " " . $this->address;
    }
}
