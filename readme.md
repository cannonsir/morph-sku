## morph-sku

> 适用于Laravel的商品属性，SKU模块实现

### #名词介绍

- 选项     
商品属性值的键名，如`颜色`,`尺寸` 等
- 属性值   
商品某选项对应的值，同一商品同一选项下可有多个属性值
- SKU   
库存控制的最小可用单位，可通过不同的属性值组合来搭配不同金额，不同库存的SKU

### #安装

**引入**

```bash
composer require gtd/morph-sku
```

**发布迁移文件**

```bash
php artisan vendor:publish --tag=morph-sku-migrations
```

**运行迁移**

```bash
php artisan migrate
```

**如果需要发布配置文件，请运行以下命令:**

```bash
php artisan vendor:publish --tag=morph-sku-config
```

### #使用

**在商品模型中引入`Gtd\MorphSku\Traits\HasSku`Trait**

```php
use Illuminate\Database\Eloquent\Model;
use Gtd\MorphSku\Traits\HasSku;

class Product extends Model
{
    use HasSku;
}
```

---

**选项新增**

```php
use Gtd\MorphSku\Models\Option;
Option::create(['name' => '尺寸']);
```

**选项删除**

```php
$option->delete();
```

---

**获取商品属性值**

```php
$poduct->attrs()->get();
$poduct->attrs;
```

**新增商品属性值**

```php
$product->addAttrValues($option, ['S', 'M', 'L']);
$product->addAttrValues('套餐', ['套餐一', '套餐二', '套餐三']);
```

**同步商品属性值**

```php
$product->syncAttrValues($option, ['红色', '白色']);
```

**移除某选项下的所有属性值**

```php
$product->removeAttrValues($option);
```

参数说明:
```php
addAttrValues($option, ...$value)
syncAttrValues($option, ...$value)
removeAttrValues($option)
```
> 1. `$option` 属性实例/属性ID/属性名称
> 2. `$value` 属性值数组 每一项将会创建或同步属性值

---

**创建(同步)SKU**

> 如果属性值存在，则更新SKU，否则创建SKU     
> sku的属性组合是建立在产品基础属性值之上的，分配sku属性值组合前请添加产品属性值

```php
$product->syncSkuWithAttrs([$attr1, $attr2, $attr3], ['amount' => 5000, 'stock' => 100]);
```
`syncSkuWithAttrs`参数说明:
> 1. `$position` 属性值组合数组,每项类型为:`属性值实例`/`属性值ID`
> 2. `$payload` SKU数据，如`金额`,`库存`等

**获取SKU**

```php
use Gtd\MorphSku\Models\Sku;
// 通过属性值组合获取sku
$sku = Sku::findByPosition($attr1, $attr2);
// 获取产品sku实例
$product->skus()->get();
```

**删除SKU**

```php
$sku->delete();
$product->skus()->delete();
```

**通过属性值组合获取SKU**

```php
use Gtd\MorphSku\Models\Sku;
Sku::findByPosition([$attr1, $attr2, $attr3])
```

**调整SKU的属性值组合**

```php
// 增加属性值组合
$sku->assignAttrs([$attr1, $attr2])
// 同步属性值组合
$sku->syncAttrs([$attr1, $attr2])
// 移除属性值组合
$sku->removeAttrs([$attr1, $attr2])
```

---

**完整示例**
```php
// 创建产品
$product = Product::create(['title' => 'phone']);

// 基础属性
$product->addAttrValues('屏幕尺寸', ['5.5', '9.9', '4.4']);
$product->addAttrValues('运营商', ['移动', '联通', '电信']);
$product->addAttrValues('CPU型号', ['骁龙730G', '麒麟960', '联发科']);

// 准备作为sku属性
$colorAttrs = $product->addAttrValues('机身颜色', ['黑色', '白色']);
$Capattrs = $product->addAttrValues('存储容量', ['6GB', '8GB', '12GB']);

// 获取属性值实例
$black = $colorAttrs->firstWhere('value', '黑色');
$white = $colorAttrs->firstWhere('value', '白色');
$sixGB = $Capattrs->firstWhere('value', '6GB');
$eightGB = $Capattrs->firstWhere('value', '8GB');

// 组合属性值，建立sku
$product->syncSkuWithAttrs([$black, $sixGB], ['amount' => 6000, 'stock' => 100]);
$product->syncSkuWithAttrs([$black, $eightGB], ['amount' => 8000, 'stock' => 100]);
$product->syncSkuWithAttrs([$white, $sixGB], ['amount' => 6666, 'stock' => 100]);
$product->syncSkuWithAttrs([$white, $eightGB], ['amount' => 8888, 'stock' => 100]);

// 获取商品及商品SKU数据
$product = $product->load('skus.attrs.option');
```
