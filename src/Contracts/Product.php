<?php

namespace Gtd\Product\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Product
{
    /**
     * 上架
     *
     * @return mixed
     */
    public function upperShelf();

    /**
     * 下架
     *
     * @return mixed
     */
    public function lowerShelf();
}