<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationCombine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_variation_combine';
    public $timestamps = false;

    protected $fillable = [
        'product_variation_id',
        'variation_value_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function delete_by_where($params)
    {
        $params = array_merge([
            'product_variation_ids' => null,
        ], $params);

        $params['product_variation_ids']=is_array($params['product_variation_ids'])?$params['product_variation_ids']:[$params['product_variation_ids']];

        return self::whereIn('product_variation_id', $params['product_variation_ids'])->delete();
    }
}
