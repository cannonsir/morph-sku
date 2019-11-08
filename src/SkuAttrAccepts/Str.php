<?php

namespace Gtd\Product\SkuAttrAccepts;

class Str extends Accept
{
    public static function getter($value)
    {
        return (string) $value;
    }

    public static function setter($value)
    {
        return (string) $value;
    }
}
