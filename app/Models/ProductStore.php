<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    protected $table = 'lck_product_store';
    protected $fillable = [
        'id',
        'product_code',
        'name',
        'amount',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
