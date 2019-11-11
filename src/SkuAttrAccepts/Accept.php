<?php

namespace Gtd\Sku\SkuAttrAccepts;

use Gtd\Sku\Contracts\SkuAttrAccept;

abstract class Accept implements SkuAttrAccept
{
    public static function getter($value)
    {
        return $value;
    }

    public static function setter($value)
    {
        return $value;
    }

    public static function inputVerify($value): bool
    {
        return true;
    }

    public static function presetVerify($value): bool
    {
        return true;
    }
}
