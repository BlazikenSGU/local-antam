<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersTemp extends Model
{
    use SoftDeletes;
    const STATUS_CANCEL = 5,
        STATUS_NEW = 1,
        STATUS_CONFIRMED = 2,
        STATUS_DELIVERING = 3,
        STATUS_FINISH = 4;
    public static $status = [
        self::STATUS_NEW        => 'Mới đặt',
        self::STATUS_CONFIRMED  => 'Đã xác nhận',
        self::STATUS_DELIVERING => 'Đang giao hàng',
        self::STATUS_FINISH     => 'Hoàn thành',
        self::STATUS_CANCEL     => 'Đã hủy',
    ];
    public static $status_payment = [
        0 => 'Chưa thanh toán',
        1 => 'Đã thanh toán',
    ];
    public static $payment_type = [
        1 => 'Tiền mặt',
        2 => 'Chuyển khoản',
        3 => 'Cổng thanh toán tự động',
        4 => 'Công nợ (chỉ dành cho nhà NPP hoặc đại lý)',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_orders_temp';
    protected $fillable = [
        'company_id',
        'user_id',
        'order_code',
        'fullname',
        'phone',
        'email',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'street',
        'height',
        'weight',
        'width',
        'total_price',
        'note',
        'status',
        'status_payment',
        'created_at',
        'updated_at',
        'send_mail_status',
        'discount_code',
        'discount_info',
        'discount_user',
        'discount_server',
        'discount_payment',
        'discount_purchase',
        'billing_information',
        'total_reduce',
        'time_slot_shipping',
        'date_shipping',
        'product_price',
        'payment_type',
        'total_reduce_point',
        'reduce_point',
        'cancel_reason',
        'tab_id',
        'tab_name',
        'total_paid',
        'password_customer',
        'user_category_id',
        'store_name',
        'note_customer',
        'sales_route',
        'username',
        'recommender',
        'status_customer',
        'cost_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['company_id'];

    public static function get_by_where($params = [], $select = '*')
    {
        $params = array_merge([
            'id'             => null,
            'order_code'     => null,
            'fullname'       => null,
            'email'          => null,
            'phone'          => null,
            'status'         => null,
            'status_payment' => null,
            'user_id'        => null,
            'limit'          => config('constants.item_perpage'),
            'pagin_path'     => null,
            'sort-asc'     => null,
        ], $params);

        $data = self::select($select);
        $data->where('company_id', config('constants.company_id'));

        if (!empty($params['id']))
            $data->where('id', $params['id']);

        if (!empty($params['order_code']))
            $data->where('order_code', 'like', "%{$params['order_code']}%");

        if (!empty($params['fullname']))
            $data->where('fullname', 'like', "%{$params['fullname']}%");

        if (!empty($params['email']))
            $data->where('email', $params['email']);

        if (!empty($params['phone']))
            $data->where('phone', 'like', "%{$params['phone']}%");

        if ($params['status'] !== null) {
            $params['status'] = !is_array($params['status']) ? [$params['status']] : $params['status'];
            $data->whereIn('status', $params['status']);
        }

        if (!empty($params['status_payment'])) {
            $params['status_payment'] = !is_array($params['status_payment']) ? [$params['status_payment']] : $params['status_payment'];
            $data->whereIn('status_payment', $params['status_payment']);
        }

        if (!empty($params['user_id'])) {
            $data->where('user_id', $params['user_id']);
        }

        if (!empty($params['sort-asc'])) {
            $data->orderBy('id', 'asc');
        } else {
            $data->orderBy('id', 'DESC');
        }



        $data = $data->paginate($params['limit'])->withPath($params['pagin_path']);
        return $data;
    }

    public static function count_by_where($params = [])
    {
        $params = array_merge([
            'status'         => null,
            'status_payment' => null,
        ], $params);

        $count = self::select('*');

        if ($params['status'] !== null) {
            $params['status'] = !is_array($params['status']) ? [$params['status']] : $params['status'];
            $count->whereIn('status', $params['status']);
        }

        if ($params['status_payment'] !== null) {
            $params['status_payment'] = !is_array($params['status_payment']) ? [$params['status_payment']] : $params['status_payment'];
            $count->whereIn('status_payment', $params['status_payment']);
        }

        $total = $count->count();

        return $total;
    }

    public static function get_detail($id, $select = '*', $with = null)
    {
        $data = self::select($select)
            ->where('id', $id);

        if ($with)
            $data->with($with);

        return $data->first();
    }

    public static function get_detail_tracking($order_code, $phone = '')
    {
        return self::where('order_code', $order_code)
            ->where('phone', $phone)->first();
    }

    public function order_details()
    {
        return $this->hasMany(OrdersDetailTemp::class, 'order_id', 'id')
            ->select([
                'id',
                'order_id',
                'product_id',
                'product_code',
                'thumbnail_path',
                'title',
                'description',
                'quantity',
                'price',
                'total_price',
                'product_variation_id',
                'product_variation_name',
                'inventory_management',
                'inventory_policy',
                'buy_out_of_stock',
                'notes',
                'percent_discount_product',
                'cash_discount_product',
                'price_in_change',
                'type_discount'
            ]);
    }

    public function user()
    {
        return $this->belongsTo(CoreUsers::class, 'user_id', 'id')
            ->select(['id', 'fullname', 'phone']);
    }

    public static function getTabId($tab_id)
    {
        return self::where('tab_id', $tab_id)->first();
    }
}
