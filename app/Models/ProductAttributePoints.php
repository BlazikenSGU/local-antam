<?php

namespace App\Models;

class ProductAttributePoints extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'lck_product_attribute_points';
    protected $fillable = [
        'product_id',
        'attribute_id',
        'point',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
