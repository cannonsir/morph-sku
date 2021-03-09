<?php

namespace Connonsir\MorphSku\Contracts;

interface OptionContract
{
    /**
     * 通过名称查询选项
     *
     * @param string $name
     * @return mixed
     */
    public static function findByName(string $name);
}