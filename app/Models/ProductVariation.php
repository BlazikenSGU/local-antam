<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_product_variation';

    protected $fillable = [
        'product_id',
        'name',
        'price',
        'product_code',
        'inventory_management',
        'inventory',
        'inventory_policy',
        'gallery',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function create_product_variation($product_variations, $product_id)
    {
        foreach ($product_variations as $v) {
            if (!isset($v['selected'])) continue;

            $variation_combination = explode('|', $v['variation_combination']);
            $variation_value_combination = explode('|', $v['variation_value_combination']);
            $name = strip_tags($v['name']);
            $price = (int)$v['price'] > 0 ? (int)$v['price'] : null;
            $product_code = strip_tags($v['product_code']);
            $product_code = $product_code ? $product_code : null;
            $inventory_management = isset($v['inventory_management']) && $v['inventory_management'] == 'on' ? 1 : 0;
            $inventory = (int)$v['inventory'] > 0 ? (int)$v['inventory'] : 0;
            $inventory_policy = isset($v['inventory_policy']) && $v['inventory_policy'] == 'on' ? 1 : 0;

            foreach ($variation_value_combination as $k2 => $v2) {
                $variation_value_combination[$k2] = mb_ucfirst(trim(strip_tags($v2)));
            }

            $product_variation = ProductVariation::create([
                'product_id'           => $product_id,
                'name'                 => implode('/', $variation_value_combination),
                'price'                => $price,
                'product_code'         => $product_code,
                'inventory_management' => $inventory_management,
                'inventory'            => $inventory,
                'inventory_policy'     => $inventory_policy,
            ]);

            foreach ($variation_value_combination as $k3 => $v3) {
                $variation_value = VariationValues::where('variation_id', $variation_combination[$k3])
                    ->where('value', $v3)
                    ->first();

                if (empty($variation_value)) {
                    $variation_value = VariationValues::create([
                        'variation_id' => $variation_combination[$k3],
                        'value'        => $v3,
                    ]);
                }
                VariationCombine::create([
                    'product_variation_id' => $product_variation->id,
                    'variation_value_id'   => $variation_value->id,
                ]);
            }
        }

        return true;
    }

    public static function update_product_variation($product_variations, $product_id)
    {
        foreach ($product_variations as $k => $v) {

            $a_variation_value_name = [];
            foreach ($v['variation_combine'] as $variation_id => $variation_value_name) {
                $a_variation_value_name[] = mb_ucfirst(trim(strip_tags($variation_value_name)));
            }

            $name = implode('/', $a_variation_value_name);
            $price = (int)$v['price'] > 0 ? (int)$v['price'] : null;
            $product_code = strip_tags($v['product_code']);
            $product_code = $product_code ? $product_code : null;
            $inventory_management = isset($v['inventory_management']) && $v['inventory_management'] == 'on' ? 1 : 0;
            $inventory = (int)$v['inventory'] > 0 ? (int)$v['inventory'] : 0;
            $inventory_policy = isset($v['inventory_policy']) && $v['inventory_policy'] == 'on' ? 1 : 0;

            $product_variation = self::get_by_id([
                'id'         => $k,
                'product_id' => $product_id,
            ]);

            if (empty($product_variation))
                continue;

            $product_variation = self::get_by_name([
                'name'       => $name,
                'product_id' => $product_id,
            ]);

            if (!empty($product_variation) && $product_variation['id'] != $k)
                continue;

            VariationCombine::delete_by_where([
                'product_variation_ids' => $k,
            ]);

            foreach ($v['variation_combine'] as $variation_id => $variation_value_name) {

                $variation_value_name = mb_ucfirst(trim(strip_tags($variation_value_name)));
                $a_variation_value_name[] = $variation_value_name;

                $variation_value = VariationValues::where('variation_id', $variation_id)
                    ->where('value', $variation_value_name)
                    ->first();

                if (empty($variation_value)) {
                    $variation_value = VariationValues::create([
                        'variation_id' => $variation_id,
                        'value'        => $variation_value_name,
                    ]);
                }

                $variation_combine = VariationCombine::where('product_variation_id', $k)
                    ->where('variation_value_id', $variation_value->id)
                    ->first();

                if (empty($variation_combine)) {
                    VariationCombine::create([
                        'product_variation_id' => $k,
                        'variation_value_id'   => $variation_value->id,
                    ]);
                }
            }

            self::find($k)->update([
                'name'                 => $name,
                'price'                => $price,
                'product_code'         => $product_code,
                'inventory_management' => $inventory_management,
                'inventory'            => $inventory,
                'inventory_policy'     => $inventory_policy,
            ]);
        }

        return true;
    }

    public static function get_product_variations_with_combine($product_id)
    {
        $results = self::selectRaw('pv.*,
        GROUP_CONCAT( vv.variation_id ORDER BY vv.variation_id ASC) AS variation_id_combination,
	    GROUP_CONCAT( vc.variation_value_id ORDER BY vv.variation_id ASC) AS variation_value_combination')
            ->from('lck_product_variation as pv')
            ->join('lck_variation_combine as vc', 'pv.id', 'vc.product_variation_id')
            ->join('lck_variation_values as vv', 'vc.variation_value_id', 'vv.id')
            ->where('pv.product_id', $product_id)
            ->groupBy('pv.id')
            ->get();

        return $results;
    }

    public static function delete_by_where($params)
    {
        $params = array_merge([
            'product_variation_ids' => null,
        ], $params);

        return self::whereIn('id', $params['product_variation_ids'])->delete();
    }

    public static function get_by_id($params)
    {
        $params = array_merge([
            'id'         => null,
            'product_id' => null,
        ], $params);

        return self::where('id', $params['id'])->where('product_id', $params['product_id'])->first();
    }

    public static function get_by_name($params)
    {
        $params = array_merge([
            'name'       => null,
            'product_id' => null,
        ], $params);

        return self::where('name', $params['name'])->where('product_id', $params['product_id'])->first();
    }

    public static function update_inventory($params)
    {
        $params = array_merge(array(
            'id'         => null,
            'product_id' => null,
            'quantity'   => 0,
        ), $params);

        if ($params['quantity'] > 0)
            return self::where('id', $params['id'])
                ->where('product_id', $params['product_id'])
                ->where('inventory_management', 1)
                ->increment('inventory', $params['quantity']);
        else
            return self::where('id', $params['id'])
                ->where('product_id', $params['product_id'])
                ->where('inventory_management', 1)
                ->decrement('inventory', abs($params['quantity']));
    }

    public static function get_inventory($params)
    {
        $params = array_merge([
            'product_id' => null,
        ], $params);

        $data = self::selectRaw('SUM(inventory) as Total')
            ->where('product_id', $params['product_id'])
            ->where('inventory_management', 1)
            ->first();

        return $data ? $data->Total : 0;
    }

    public static function count_by_variation_value($product_id)
    {
//        \DB::enableQueryLog();

        $data = self::selectRaw('count(DISTINCT(lck_variation_combine.variation_value_id) ) AS Total ')
            ->from('lck_product_variation')
            ->join('lck_variation_combine', 'lck_product_variation.id', 'lck_variation_combine.product_variation_id')
            ->join('lck_variation_values', 'lck_variation_combine.variation_value_id', 'lck_variation_values.id')
            ->where('lck_product_variation.product_id', $product_id)
            ->where('lck_variation_values.variation_id', 2)
            ->first();

//        echo "<pre>";
//        print_r(\DB::getQueryLog());
//        exit;

        return $data ? $data->Total : 0;
    }
}
