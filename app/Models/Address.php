<?php

namespace App\Models;

use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //use SoftDeletes;

    const IS_DEFAULT_RECIPIENT = 1;
    const IS_WAREHOUSE = 1;
    const TYPE_INVENTORY = 1;
    const IS_RETURN = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_address';

    protected $fillable = [
        'company_id',
        'user_id',
        'province_id',
        'district_id',
        'ward_id',
        'street_name',
        'province_name',
        'district_name',
        'ward_name',
        'product_type',
        'name',
        'is_default',
        'phone',
        'required_note',
        'note',
        'money_fail',
        'payment_type',
        'transport_unit',
        'pickup_area',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function get_by_where($params)
    {
        $params = array_merge(array(
            'is_default_recipient' => null,
            'is_warehouse'         => null,
            'is_return'            => null,
            'user_id'              => null,
            'limit'                => 10,
            'pagin_path'           => null,
        ), $params);


        $data = self::orderBy('created_at', 'DESC');

        if (!empty($params['user_id']))
            $data->where('user_id', $params['user_id']);

        if ($params['is_default_recipient'] !== null)
            $data->where('is_default_recipient', $params['is_default_recipient']);

        if ($params['is_warehouse'] !== null)
            $data->where('is_warehouse', $params['is_warehouse']);

        if ($params['is_return'] !== null)
            $data->where('is_return', $params['is_return']);

        $data = $data->paginate($params['limit'])->withPath($params['pagin_path']);

        return $data;
    }

    public static function get_full_address($params)
    {
        if (!isset($params['street_name']) || !isset($params['province_id']) || !isset($params['district_id']) || !isset($params['ward_id']))
            return null;

        $province = Province::findOrFail($params['province_id']);
        $district = District::findOrFail($params['district_id']);
        $ward = Ward::findOrFail($params['ward_id']);

        $address = $params['street_name'] . ', ' . $ward->name . ', ' . $district->name . ', ' . $province->name;

        return $address;
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id')
            ->select(['id', 'name']);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id')
            ->select(['id', 'name']);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'id')
            ->select(['id', 'name']);
    }
}
