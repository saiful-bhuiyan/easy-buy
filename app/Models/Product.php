<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,Sluggable,SoftDeletes;

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function SubCategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function ProductColor()
    {
        return $this->hasMany(ProductColor::class,'product_id');
    }

    public function ProductSize()
    {
        return $this->hasMany(ProductSize::class,'product_id');
    }

    public function ProductImage()
    {
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function OrderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    // get first few image only

    public function maxImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order_by')->take(4);
    }

    // Filter Product

    public static function filterProducts($requestData)
    {
        $sub_category_list = rtrim($requestData->sub_category_list, ',');
        $sub_category_array = $sub_category_list ? explode(',', $sub_category_list) : [];

        $brand_list = rtrim($requestData->brand_list, ',');
        $brand_array = $brand_list ? explode(',', $brand_list) : [];

        $color_list = rtrim($requestData->color_list, ',');
        $color_array = $color_list ? explode(',', $color_list) : [];

        $price_start = preg_replace("/[^0-9]/", "",$requestData->get_start_price ? $requestData->get_start_price : '');
        $price_end = preg_replace("/[^0-9]/", "",$requestData->get_end_price ? $requestData->get_end_price : '');

        $query = self::query()->where('status', 1);

        if (!empty($sub_category_array)) {
            $query->whereIn('sub_category_id', $sub_category_array);
        }
        if (!empty($brand_array)) {
            $query->whereIn('brand_id', $brand_array);
        }
        if (!empty($color_array)) {
            $query->whereHas('ProductColor', function ($q) use ($color_array) {
                $q->whereIn('color_id', $color_array);
            });
        }
        if ($price_start != "" && $price_end != "") {
            $query->whereBetween('price', [$price_start, $price_end]);
        }

        $getProduct = $query->paginate(2);

        return $getProduct;
    }

    // Related Product

    public static function getRelatedProduct($subcategoryId, $productId)
    {
        $products = self::where('sub_category_id', $subcategoryId)
                    ->where('status', 1)
                    ->where('id','!=',$productId)
                    ->get();

        if ($products->isEmpty()) {
            $category = SubCategory::find($subcategoryId)->category_id;
            
            $products = self::where('category_id', $category)
                    ->where('status', 1)
                    ->where('id','!=',$productId)
                    ->get();
        }
        return $products;
    }
}
