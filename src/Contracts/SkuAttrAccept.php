<?php

namespace Gtd\Product\Contracts;

interface SkuAttrAccept
{
    /**
     * 获取值
     *
     * @param mixed $value
     * @return mixed
     */
    public static function getter($value);

    /**
     * 设置值
     *
     * @param mixed $value
     * @return mixed
     */
    public static function setter($value);

    /**
     * 输入值验证
     *
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public static function inputVerify($value): bool;

    /**
     * 预设值验证(sku_value)
     *
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public static function presetVerify($value): bool;
}