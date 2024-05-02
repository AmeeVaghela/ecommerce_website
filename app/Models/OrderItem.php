<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_color_id',
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id' , 'id');
    }

    public function productColor()
    {
        return $this->hasMany(ProductColor::class, 'product_color_id' , 'id');
    }
}
