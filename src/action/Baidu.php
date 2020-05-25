<?php
namespace Ocr\action;

use Ocr\inter\Ocr;
use Ocr\lib\Baidu as Lib;

class Baidu implements Ocr
{
    protected $lib;
    function __construct(Lib $lib)
    {
        $this->lib = $lib;
    }

    /**
     * 识别户口本内容
     * @param $image_base_64
     * @return array
     */
    function accountBook($image_base_64)
    {
        return $this->lib->houseHold($image_base_64);
    }
}
