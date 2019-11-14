## morph-sku

> 适用于Laravel的商品属性，SKU模块实现

### 安装

**引入**

```bash
composer require gtd/morph-sku
```

**运行迁移**

```bash
php artisan migrate
```

如果需要发布迁移及配置文件，请运行以下命令:

- 配置文件

```bash
php artisan vendor:publish --tag=morph-sku-config
```

- 迁移文件

```bash
php artisan vendor:publish --tag=morph-sku-migrations
```

### 数据结构

> 选项 属性键值 sku 属性键值-sku

### 使用

**在商品模型中引入`Gtd\MorphSku\Traits\HasSku`Trait**

```php
use Illuminate\Database\Eloquent\Model;
use Gtd\MorphSku\Traits\HasSku;

class Product extends Model
{
    use HasSku;
}
```

**选项**

> 各属性的键名

```php
// 创建选项
$option = Option::create(['name' => '尺寸']);
// 删除选项
$option->delete();
```

**属性值**

- 新增商品属性值  

```php
// 新增选项值
$option = Option::create(['name' => '尺寸']);

// 参数一需传递选项实例或id，传递字符串会自动创建, 参数二传递该属性的值
$attrs = $product->addAttrValues($option, ['S', 'M', 'L']);
$attrs = $product->addAttrValues('套餐', ['套餐一', '套餐二', '套餐三']);

// 返回值$attrs为属性值集合
```

- 同步商品属性值

```php
$option = Option::first();
$product->syncAttrValues($option, ['红色', '白色']);
```

- 获取商品属性值

```php
$attrs = $poduct->attrs;
$attrs = $poduct->attrs()->get();
```

- 移除某选项及属性值

```php
$product->removeAttrValues($option);
```

**SKU**

- 创建SKU

> sku的属性组合是建立在产品基础属性值之上的，分配sku属性值组合前需添加产品属性值

自定义创建与分配

```php
$sku = $product->skus()->create(['amount' => 5000, 'stock' => 100]);
$sku->attrs()->attach([1, 2, 3]);   // 绑定产品属性值
```

通过属性值组合创建sku

```php
// 参数一传递属性值组合id数组，参数二传递sku表数据 返回新增
$sku = $product->addSkuWithAttrs([1, 2, 3], ['amount' => 5000, 'stock' => 100]);
```

> 使用此方法创建会自动验证属性值是属于商品。所以更推荐使用

- 获取SKU

```php
// 通过属性值组合获取sku
$sku = Sku::findByPosition($attr1, $attr2);
// 获取产品sku实例
$product->skus()->get();
// 获取产品sku矩阵
$skuMatrix = $product->getSkuMatrixAttribute();
// 产品携带sku矩阵
$product->append('sku_matrix')->toArray();
```

- 删除SKU

```php
$sku->delete();
$product->skus()->delete();
```

### 完整示例
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
$product->addSkuWithAttrs([$black, $sixGB], ['amount' => 6000, 'stock' => 100]);
$product->addSkuWithAttrs([$black, $eightGB], ['amount' => 8000, 'stock' => 100]);
$product->addSkuWithAttrs([$white, $sixGB], ['amount' => 6666, 'stock' => 100]);
$product->addSkuWithAttrs([$white, $eightGB], ['amount' => 8888, 'stock' => 100]);

// 获取产品sku列表
$skus = $product->skus()->get();
// 获取产品sku矩阵
$skuMatrix = $product->getSkuMatrixAttribute();
// 产品携带sku矩阵
$product->append('sku_matrix')->toArray();
```
