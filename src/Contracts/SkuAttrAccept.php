<?php

namespace Gtd\Product\Contracts;

interface SkuAttrAccept
{
    /**
     * 名称
     *
     * @return string
     */
    public static function getName():string;

    /**
     * 输入值验证
     *
     * @param $data mixed 数据
     * @return bool
     */
    public function inputVerify($data): bool;

    /**
     * 预设值验证(sku_value)
     *
     * @param $data
     * @return bool
     */
    public function presetVerify($data): bool;

    /**
     * 名称
     *
     * @return string 名称
     */
    public function __toString(): string ;
}