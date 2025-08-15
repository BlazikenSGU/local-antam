<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationValues extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_variation_values';

    protected $fillable = [
        'variation_id',
        'value',
        'color',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_variants($product_id)
    {
        $data = self::select([
            'lck_variation_values.variation_id',
            'lck_variations.name',
            'lck_product_variation.gallery',
            'lck_variation_combine.variation_value_id',
            'lck_variation_values.value',
            'lck_variation_values.color',
        ])
            ->from('lck_product_variation')
            ->join('lck_variation_combine', 'lck_variation_combine.product_variation_id', 'lck_product_variation.id')
            ->join('lck_variation_values', 'lck_variation_combine.variation_value_id', 'lck_variation_values.id')
            ->join('lck_variations', 'lck_variation_values.variation_id', 'lck_variations.id')
            ->where('lck_product_variation.product_id', $product_id)
            ->groupBy('lck_variation_combine.variation_value_id')
            ->orderByRaw('lck_variation_values.variation_id ASC, lck_variation_combine.variation_value_id ASC');

        return $data->get();
    }

    public static function get_group_variations($product_id)
    {
        $data = self::get_variants($product_id);

        $variants = [];

        if (count($data)) {
            foreach ($data as $item) {
                if (!isset($variants[$item->variation_id])) {
                    $variants[$item['variation_id']] = [
                        'variation_id' => $item->variation_id,
                        'name'         => $item->name,
                    ];
                }
                $variants[$item->variation_id]['items'][] = [
                    'variation_value_id' => $item->variation_value_id,
                    'value'              => $item->value,
                    'color'              => $item->color,
                    'gallery'            => $item->gallery,
                ];
            }
            $variants = array_values($variants);
        }

        return $variants;
    }

    public static function get_validation_admin()
    {
        return [
            'value'        => 'required|string',
            'variation_id' => 'required|integer',
        ];
    }
}
