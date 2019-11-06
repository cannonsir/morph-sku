<?php

namespace Gtd\Product\Contracts;

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