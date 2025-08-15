<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Links;

class OrdersDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_orders_detail';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_name',
        'product_code',
        'product_voluem',
        'quantity',

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
}
