<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsTypes extends Model
{
    protected $table = 'lck_products_types';

    protected $fillable = [
        'id',
        'product_id',
        'type_id',
        'created_at',
        'updated_at',
    ];
}
