<?php

namespace Connonsir\MorphSku\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface AttrContract
{
    /**
     * 获取所属选项
     *
     * @return BelongsTo
     */
    public function option(): BelongsTo;

    /**
     * 获取所属产品
     *
     * @return MorphTo
     */
    public function producible(): MorphTo;

    /**
     * 获取使用此键值的sku
     *
     * @return BelongsToMany
     */
    public function skus(): BelongsToMany;
}