<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Links;

class OrdersDetailTemp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_orders_detail_temp';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_code',
        'thumbnail_path',
        'title',
        'description',
        'quantity',
        'price',
        'total_price',
        'product_variation_id',
        'product_variation_name',
        'inventory_management',
        'inventory_policy',
        'buy_out_of_stock',
        'notes',
        'original_cost',
        'percent_discount_product',
        'cash_discount_product',
        'price_in_change',
        'type_discount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = array('thumbnail_src');

    public function getThumbnailSrcAttribute()
    {
        return Links::ImageLink($this->thumbnail_path);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')
            ->select(['id', 'is_multilevel']);
    }

    public static function checkProductOrder($product_id, $order_id)
    {
        return self::where('product_id', $product_id)
            ->where('order_id', $order_id)
            ->first();
    }
}
