<?php

namespace Gtd\Product\SkuAttrAccepts;

class CustomizableCheckBox extends Accept
{
    public static function getName(): string
    {
        return \Str::snake(class_basename(self::class));
    }
}
