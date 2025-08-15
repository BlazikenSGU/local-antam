<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
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
        self::STATUS_DELIVERING => 'Đã làm xong',
        self::STATUS_FINISH     => 'Hoàn thành',
        self::STATUS_CANCEL     => 'Đã hủy',
    ];


    const status_ghn1 =  'Đơn mới';
    const status_ghn2 =  'Chờ lấy hàng';
    const status_ghn3 =  'Đang giao';
    const status_ghn4 =  'Đang hoàn hàng';
    const status_ghn5 =  'Chờ xác nhận giao lại';
    const status_ghn6 =  'Hoàn tất';
    const status_ghn7 =  'Đơn hủy';
    const status_ghn8 =  'Hàng thất lạc - Hư hỏng';

    const status_ghn1_1 =  'create_order';
    const status_ghn2_1 =  'ready_to_pick';
    const status_ghn3_1 =  'picking';
    const status_ghn4_1 =  'money_collect_picking';
    const status_ghn5_1 =  'picked';
    const status_ghn6_1 =  'storing';
    const status_ghn7_1 =  'transporting';
    const status_ghn8_1 =  'sorting';
    const status_ghn9_1 =  'delivering';
    const status_ghn10_1 =  'money_collect_delivering';
    const status_ghn11_1 =  'delivery_fail';
    const status_ghn12_1 =  'return';
    const status_ghn13_1 =  'return_transporting';
    const status_ghn14_1 =  'return_sorting';
    const status_ghn15_1 =  'returning';
    const status_ghn16_1 =  'return_fail';
    const status_ghn17_1 =  'waiting_to_return';
    const status_ghn18_1 =  'delivered';
    const status_ghn19_1 =  'returned';
    const status_ghn20_1 =  'cancel';
    const status_ghn21_1 =  'exception';
    const status_ghn22_1 =  'lost';
    const status_ghn23_1 =  'damage';


    const STATUS_GHN_1 =    [
        self::status_ghn1_1
    ];

    const STATUS_GHN_2 =    [
        self::status_ghn2_1,
        self::status_ghn3_1,
        self::status_ghn4_1
    ];

    const STATUS_GHN_3 = [
        self::status_ghn5_1,
        self::status_ghn6_1,
        self::status_ghn7_1,
        self::status_ghn8_1,
        self::status_ghn9_1,
        self::status_ghn10_1,
        self::status_ghn11_1
    ];

    const STATUS_GHN_3_2 = [
        self::status_ghn5_1,
        self::status_ghn6_1,
        self::status_ghn7_1,
        self::status_ghn8_1,
        self::status_ghn10_1,
    ];

    const STATUS_GHN_4 = [
        self::status_ghn12_1,
        self::status_ghn13_1,
        self::status_ghn14_1,
        self::status_ghn15_1,
        self::status_ghn16_1
    ];

    const STATUS_GHN_5 = [
        self::status_ghn17_1,
    ];

    const STATUS_GHN_6 = [
        self::status_ghn18_1,
        self::status_ghn19_1,
    ];

    const STATUS_GHN_7 = [
        self::status_ghn20_1,
    ];

    const STATUS_GHN_8 = [
        self::status_ghn21_1,
        self::status_ghn22_1,
        self::status_ghn23_1,
    ];

    const STATUS_RETURNED = [
        self::status_ghn19_1
    ];

    const LIST_STATUS_GHN = [
        self::status_ghn1,
        self::status_ghn2,
        self::status_ghn3,
        self::status_ghn4,
        self::status_ghn5,
        self::status_ghn6,
        self::status_ghn7,
        self::status_ghn8
    ];

    public static $Keystatus = [
        1 => 'Đơn nháp',
        2 => 'Chờ bàn giao',
        3 => 'Đã bàn giao - Đang giao',
        4 => 'Đã bàn giao - Đang hoàn hàng',
        5 => 'Chờ xác nhận giao lại',
        6 => 'Hoàn tất',
        7 => 'Đơn hủy',
        8 => 'Hàng thất lạc - Hư hỏng',
    ];

    public static $Keyrequired_note = [
        1 => 'Không cho xem hàng',
        2 => 'Cho xem hàng - Không cho thử',
        3 => 'Cho thử hàng',
    ];


    public static $status_payment = [
        0 => 'Chưa thanh toán',
        1 => 'Đã thanh toán',
    ];
    public static $payment_type = [
        1 => 'Tiền mặt',
        2 => 'Chuyển khoản',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lck_orders';
    protected $fillable = [
        'company_id',
        'shop_id',
        'user_id',
        'order_code',
        'fullname',
        'phone',
        'email',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'note',
        'required_note',
        'to_name',
        'to_phone',
        'to_address',
        'to_ward_name',
        'to_district_name',
        'to_province_name',
        'to_province',
        'to_district',
        'to_ward',
        'cod_amount',
        'weight',
        'status',
        'status_payment',
        'caGiaohang',
        'length',
        'width',
        'height',
        'payment_method',
        'payment_fee',
        'fee_shopId',
        'total_fee',
        'statusName',
        'cod_failed_amount',
        'product_type',
        'insurance_value',
        'total_cost',
        'order_code_custom',
        'product_type_cost',
        'cod_collect_date',
        'cod_transfer_date',
        'finish_date',
        'cod_failed_collect_date',
        'main_service',
        'insurance',
        'paid_date',
        'insurance_fee',
        'return_address',
        'return_province',
        'return_province_name',
        'return_district',
        'return_district_name',
        'return_ward',
        'return_ward_name',
        'return_phone',
        'province_name',
        'district_name',
        'ward_name',
        'ConvertedWeight',
        'R2S',
        'Return_k',
        'Return',
        'ReturnAgain',
        'PartialReturnCode',
        'phi_gh1p',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
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
            'key'        => null,
            'limit'          => config('constants.item_perpage'),
            'pagin_path'     => null,
            'date'     => null,
        ], $params);

        $data = self::select($select);
        $data->where('company_id', 0);

        if (!empty($params['user_id']) and $params['user_id'] != 168)
            $data->where('user_id', $params['user_id']);

        if (!empty($params['order_code'])) {

            $data->where('order_code', $params['order_code'])
                ->orWhere('phone', $params['order_code'])
                ->orWhere('to_name', $params['order_code'])
                ->orWhere('to_phone', $params['order_code'])
                ->orWhere('fullname', 'like', '%' . $params['order_code'] . '%')
                ->where('user_id', $params['user_id']);
        }


        if (!empty($params['status']))
            $data->where('status', $params['status']);
        if (!empty($params['date']))
            $data->whereDate('created_at', $params['date']);
        if (!empty($params['key'])) {
            $statusMappings = [
                1 => ['create_order'],
                2 => ['ready_to_pick', 'picking', 'money_collect_picking'],
                3 => ['picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering', 'delivery_fail'],
                4 => ['return', 'return_transporting', 'return_sorting', 'returning', 'return_fail'],
                5 => ['waiting_to_return'],
                6 => ['delivered', 'returned'],
                7 => ['cancel'],
                8 => ['exception', 'lost', 'damage'],
                //                8 => ['waiting_to_return'],
                //                9 => ['cancel'],
            ];

            $status = $statusMappings[$params['key']] ?? [];
            $data->whereIn('statusName', $status);
        }


        $data->orderBy('id', 'DESC');

        $data = $data->paginate($params['limit'])->withPath($params['pagin_path']);

        return $data;
    }


    public static function get_by_where_distinct($params = [], $select = '*')
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
            'key'            => null,
            'limit'          => config('constants.item_perpage'),
            'pagin_path'     => null,
            'date'           => null,
        ], $params);

        $data = self::select($select)->where('company_id', 0);

        if (!empty($params['order_code'])) {

            $data->where('order_code', $params['order_code'])
                ->orWhere('phone', $params['order_code'])
                ->orWhere('to_name', $params['order_code'])
                ->orWhere('to_phone', $params['order_code'])
                ->orWhere('fullname', 'like', '%' . $params['order_code'] . '%')
                ->where('user_id', $params['user_id']);
        }

        if (!empty($params['user_id']))
            $data->where('user_id', $params['user_id']);
        if (!empty($params['status']))
            $data->where('status', $params['status']);
        if (!empty($params['date']))
            $data->whereDate('created_at', $params['date']);
        if (!empty($params['key'])) {
            //            $statusMappings = [
            //                1 => ['create_order'],
            //                2 => ['picked', 'ready_to_pick'],
            //                3 => ['delivering', 'picking'],
            //                4 => ['delivered'],
            //                5 => ['delivery_fail'],
            //                6 => ['returned'],
            //                7 => ['damage'],
            //                8 => ['waiting_to_return'],
            //                9 => ['cancel'],
            //            ];
            $statusMappings = [
                1 => ['create_order'],
                2 => ['ready_to_pick', 'picking', 'money_collect_picking'],
                3 => ['picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering', 'delivery_fail'],
                4 => ['return', 'return_transporting', 'return_sorting', 'returning', 'return_fail'],
                5 => ['waiting_to_return'],
                6 => ['delivered', 'returned'],
                7 => ['cancel'],
                8 => ['exception', 'lost', 'damage'],
                //                8 => ['waiting_to_return'],
                //                9 => ['cancel'],
            ];
            $status = $statusMappings[$params['key']] ?? [];
            $data->whereIn('statusName', $status);
        }

        $data->orderBy('created_at', 'DESC')->groupBy('created_at');

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
            ->where('id', $id)
            ->where('company_id', config('constants.company_id'));

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
        return $this->hasMany(OrdersDetail::class, 'order_id', 'id')
            ->select([
                'id',
                'order_id',
                'product_id',
                'product_code',
                'specifications',
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
                'amount_discount',
                'price_after_discount',
            ]);
    }

    public function user()
    {
        return $this->belongsTo(CoreUsers::class, 'user_id', 'id')
            ->select(['id', 'fullname', 'phone']);
    }

    public function ban()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id')
            ->select(['id', 'name']);
    }

    public function chinhanh()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id')
            ->select(['id', 'name']);
    }

    public static function NumberOrderStatus($key, $date = null)
    {
        $statusMappings = [
            1 => ['create_order'],
            2 => ['ready_to_pick', 'picking', 'money_collect_picking'],
            3 => ['picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_delivering', 'delivery_fail'],
            4 => ['return', 'return_transporting', 'return_sorting', 'returning', 'return_fail'],
            5 => ['waiting_to_return'],
            6 => ['delivered', 'returned'],
            7 => ['cancel'],
            8 => ['exception', 'lost', 'damage'],
        ];

        $UserId = Auth()->guard('backend')->user()->id;

        $status = $statusMappings[$key] ?? [];

        if ($date) {
            $a = date('Y-m-d');
            return count(self::whereIn('statusName', $status)->where('user_id', $UserId)->whereDate('created_at', $a)->get());
        }

        return count(self::whereIn('statusName', $status)->where('user_id', $UserId)->get());
    }

    public function status()
    {
        return $this->belongsTo(StatusName::class, 'statusName', 'key');
    }

    public function doisoat()
    {
        return $this->hasOne(DoiSoat::class, 'OrderCode', 'order_code');
    }
}
