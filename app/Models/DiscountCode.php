<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $table = 'lck_discount_code';
    protected $fillable = [
        'company_id',
        'title',
        'code',
        'type',
        'value',
        'minimum_price',
        'limit',
        'used_count',
        'user_ids',
        'start_date',
        'end_date',
        'status',
    ];

    public static function get_validation_admin($is_edit = false)
    {
        $validate = [
            'title'         => 'required|string',
            'company_id'    => 'nullable|integer',
            'code'          => 'required|string|unique:lck_discount_code,code',
            'type'          => 'required|in:1,2',
            'status'        => 'required|in:0,1',
            'user_ids'        => 'required|string',
            'value'         => 'required|numeric',
            'minimum_price' => 'nullable|integer',
            'limit'         => 'nullable|integer',
            'start_date'    => 'required|date_format:Y-m-d H:i:s',
            'end_date'      => 'required|date_format:Y-m-d H:i:s|after:start_date',
        ];

        if ($is_edit)
            unset($validate['code']);

        return $validate;
    }

    public static function get_by_where($params = [])
    {
        $params = array_merge(
            [
                'id'         => null,
                'code'       => null,
                'status'     => null,
                'type'       => null,
                'user_ids'       => null,
                'pagin_path' => null,
                'limit'      => 10,
                'pagin'      => true,
            ],
            $params
        );

        $data = self::select(['*']);

        if ($params['id'])
            $data->where('id', $params['id']);

        if ($params['code'])
            $data->whereNotIn('code', $params['code']);

        if ($params['status'])
            $data->where('status', $params['status']);

        if ($params['type'])
            $data->where('type', $params['type']);

        if ($params['user_ids'])
            $data->where('user_ids', $params['user_ids']);

        $data->orderBy('created_at', 'DESC');

        if ($params['pagin']) {
            $data = $data->paginate($params['limit'])
                ->withPath($params['pagin_path']);
        } else {
            if ($params['limit'])
                $data = $data->limit($params['limit']);

            $data = $data->get();
        }

        return $data;
    }

    public static function get_available($params)
    {
        $params = array_merge([
            'code' => null,
            'user_ids' => null,
        ], $params);

        return self::where('code', $params['code'])
            ->where('user_ids', $params['user_ids'])
            ->where('status', 1)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->where('end_date', '>=', date('Y-m-d H:i:s'))
            ->first();

    }
}
