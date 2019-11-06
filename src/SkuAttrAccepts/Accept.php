<?php

namespace Gtd\Product\SkuAttrAccepts;

use Gtd\Product\Contracts\SkuAttrAccept;

abstract class Accept implements SkuAttrAccept
{
    public static function getName(): string
    {
        throw new \RuntimeException('Sku accepted name not set');
    }

    public function inputVerify($data): bool
    {
        return false;
    }

    public function presetVerify($data): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return static::getName();
    }

    public static function isAllow($acceptName): bool
    {
        foreach (config('product_sku.accepts') as $acceptClass) {
            if ($acceptClass::getName() === $acceptName){
                return true;
                break;
            }
        }

        return false;
    }
}
