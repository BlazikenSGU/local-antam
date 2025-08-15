<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingFee extends Model
{
    //
    protected $table = 'lck_fee';
    protected $fillable = [
        'id',
        'user_id',
        'shop_id',
        'cost',
    ];
    public static function get_by_where($user_id, $shop_id)
    {

        $data = self::where('user_id', $user_id)->where('shop_id', $shop_id)->first();
        return $data;
    }

}
