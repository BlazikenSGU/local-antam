<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_basket';
    protected $fillable = [
        'company_id',
        'user_id',
        'device_id',
        'product_id',
        'product_variation_id',
        'quantity',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['company_id'];

    public static function get_by_where($params)
    {
        $params = array_merge([
            'user_id'    => null,
            'device_id'  => null,
            'module_id'  => null,
            'item_ids'   => null,
            'pagin'      => true,
            'pagin_path' => null,
            'limit'      => config('constants.item_perpage')
        ], $params);

        $data = self::select(\DB::raw("*"))
            ->with(['products',]);

        $data->orderBy('created_at', 'DESC');

        if ($params['item_ids'])
            $data->whereIn('id', $params['item_ids']);

        if ($params['user_id'])
            $data->where('user_id', $params['user_id']);

        if ($params['device_id'])
            $data->where('device_id', $params['device_id']);

        if ($params['pagin']) {
            $data = $data->paginate($params['limit'])
                ->withPath($params['pagin_path']);
        } else {
            $data = $data->get();
        }

        return $data;
    }

    public static function get_all_product($params)
    {
        $params = array_merge([
            'status'     => [1],
            'user_id'    => null,
            'device_id'  => null,
            'module_id'  => null,
            'item_ids'   => null,
            'group'      => true,
            'pagin'      => true,
            'pagin_path' => null,
            'limit'      => config('constants.item_perpage')
        ], $params);
        $select = [
            'b.id as basket_item_id',
            'b.product_variation_id',
            'b.product_parent_id',
            'b.quantity',
            'p.id as product_id',
            'p.title',
            'p.price',
            'p.specifications',
            'p.price_old',
            'p.description',
            'p.product_type_id',
            'p.thumbnail_id',
            //            'pv.id as product_variation_id',
            'pv.name as product_variation_name',
            'pv.price as product_variation_price',
            'pv.product_code as product_variation_product_code',
            'pv.inventory as product_variation_inventory',
            'pv.inventory_policy as inventory_policy',
            'b.created_at',
            'b.updated_at'
        ];
        $data = self::select(\DB::raw(implode(',', $select)))
            ->from('lck_basket as b')
            ->join('lck_product as p', 'p.id', 'b.product_id')
            ->leftJoin('lck_product_variation as pv', 'pv.id', 'b.product_variation_id');

        $data->with(['thumbnail']);

        $data->orderBy('b.created_at', 'DESC');
        $data->where('b.company_id', config('constants.company_id'));
        if ($params['status'])
            $data->whereIn('p.status', $params['status']);

        if ($params['user_id'])
            $data->where('b.user_id', $params['user_id']);

        if ($params['device_id'])
            $data->where('b.device_id', $params['device_id']);

        if ($params['item_ids'])
            $data->whereIn('b.id', $params['item_ids']);

        if ($params['pagin']) {
            $data = $data->paginate($params['limit'])
                ->withPath($params['pagin_path']);
        } else {

            $data = $data->get();
        }

        return $data;
    }

    public function thumbnail()
    {
        return $this->belongsTo(Files::class, 'thumbnail_id', 'id')->select(['id', 'file_path']);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')
            ->select([
                'id',
                'title',
                'price',
                'thumbnail_id',
            ]);
    }

}
