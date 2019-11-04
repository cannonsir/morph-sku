## 商品模块
- product_categories 商品分类
- products 商品
- product_skus 商品sku
- product_arrtibute_keys 商品sku属性键
- product_arrtibute_values 商品sku属性值


## 订单相关
> 独立封装?
- sku_units 对应商品sku数量，涉及运输/促销/退货
- adjustments 调整价格表,不确定性影响以上3张表,用于处理额外调整费用。运费等
- shipment 运输信息
- payment 支付信息
