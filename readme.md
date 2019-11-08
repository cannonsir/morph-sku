<div align="center"><h1>商品模块</h1></div>

> 商品,sku业务逻辑模块


### 安装
**引入**
```bash
composer require gtd/product
```

**运行迁移**
```bash
php artisan migrate
```

### 使用



 sku键/值 都与sku表有关系，与产品表无关系，sku表最终只需要在键值的组合上给予价格和库存

 如果没有sku，下单对象就直接是产品，否则还是sku
 
 
 ~~sku属性的值会影响其它shu属性~~
 
 ```php
$category = Category::first();
$category->
```