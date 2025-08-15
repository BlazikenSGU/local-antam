<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeValues extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;

    protected $table = 'lck_product_attribute_values';
    protected $fillable = [
        'product_id',
        'attribute_value_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
