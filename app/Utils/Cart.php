<?php

namespace App\Utils;

class Cart
{
    public static $_cart_key = 'basket';

    public static function get()
    {
        $data = session(self::$_cart_key);

        return $data ? unserialize($data) : [];
    }

    public static function get_processed()
    {
        $cart = self::get();

        $product_ids = [];
        $product_variation_ids = [];

        foreach ($cart as $v) {
            $product_ids[] = $v['product_id'];

            if ($v['product_variation_id'])
                $product_variation_ids[] = $v['product_variation_id'];
        }
        return [
            'product_ids'           => array_unique($product_ids),
            'product_variation_ids' => array_unique($product_variation_ids),
        ];
    }

    public static function update($product_id, $product_variation_id = null, $quantity = 1, $increase = false)
    {
        $cart = self::get();

        $exists = false;
        foreach ($cart as $k => $v) {
            if ($v['product_id'] == $product_id && $v['product_variation_id'] == $product_variation_id) {
                $exists = true;
                if ($quantity == 0) {
                    unset($cart[$k]);
                } else {
                    if ($increase) {
                        $cart[$k]['quantity'] = $cart[$k]['quantity'] + $quantity;
                    } else {
                        $cart[$k]['quantity'] = $quantity;
                    }
                }
                break;
            }
        }

        if (!$exists && $quantity > 0) {
            $cart[] = [
                'product_id'           => $product_id,
                'product_variation_id' => $product_variation_id,
                'quantity'             => $quantity,
            ];
        }

        return session([self::$_cart_key => serialize($cart)]);
    }

    public static function delete_item($item_id = null)
    {
        if ($item_id === null) return false;

        $cart = self::get();
        if (isset($cart[$item_id])) {
            unset($cart[$item_id]);
            session([self::$_cart_key => serialize($cart)]);
            return true;
        }
        return false;
    }

    public static function get_total_items()
    {
        $cart = self::get();
        return count($cart);
    }

    public static function clear()
    {
        return session([self::$_cart_key => []]);
    }
}
