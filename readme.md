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

### 数据结构
- 分类
商品与sku属性都要在某分类下

- 商品
商品包含标题，分类id，主图，详情，属性，sku信息

- sku
商品的sku
sku为最小库存单位，是包含明确属性的
部分商品如果没有sku？？？
sku属性和参数属性都是属性。如何区分

- 属性
属性用于商品参数，sku选择。对属性值的类型有影响，
部分属性只能用于参数展示
属性可用于搜索商品, 可搜索字段应该由外部加上,属性值有部分可以自定义，自定义值的字段如何搜索,一般都是通过属性值id搜索
属性需不需要加上类型字段, 感觉可以外部加上，不建议分成2张表，因为都可以被搜索
部分分类下属性也可以自定义

- 属性值
预设的属性值
部分属性值可以自定义？？？

- 产品 与 属性值 或 属性 多对多多态
id 
has_attribute_id 
has_attribute_type 拥有此属性的模型（产品或sku）
其实拥有属性的好像都是产品，而sku只拥有产品的部分属性
attribute_id 属性ID 
attribute_value_id 可以为空，部分sku属性可以自定义值
attribute_value sku属性自定义值内容

有属性的模型：商品，sku
部分商品没有sku，所以这样设计能够使商品独立拥有属性
可以通过sku获取sku对应的属性列表，以及值
应该可以实现搜索：
某分类下颜色为xx的16GB机型
select products category_id = xx join sku and sku.attribute_value_id=xx and attribute_value_id = 16gb

$product->attributes(); // 所有属性列表

感觉商品部分不涉及，设计一个HasSku Trait来引用。此包主要设计sku




### 架构
- 事件触发


### 使用
**引入Trait**

**创建选项键**
```php
$option = Option::create(['name' => '尺寸']);
```
**新增商品参数属性**
```php
$option = Option::create(['name' => '尺寸']);
// 批量新增属性值
$product->addAttrValues($option, ['S', 'M', 'L']);
```
**新增商品sku**
```php
$product->skus()->create(['amount' => 5888, 'stock' => 999]);
```
**给商品sku分配属性键值对**
新增sku前需确保属性键值对已存在于产品
```php

```