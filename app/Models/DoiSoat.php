<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoiSoat extends Model
{
    protected $table = 'lck_doi_soat';

    protected $fillable = [
        'doisoat',
        'OrderCode',
        'PartialReturnCode',
        'order_code_custom',
        'ShopID',
        'IDUser',
        'created_at',
        'ngaygiaohoanthanhcong',
        'tinhtrangthutienGTB',
        'statusName',
        'CODAmount',
        'cod_failed_amount',
        'MainService',
        'R2S',
        'Insurance',
        'Return',
        'phigiao1lan',
        'tongphi',
        'tongdoisoat',
        'type',
        'IdDoiSoatUser',
        'fee_shopId',
        'weight'
    ];

    /**
     * Relationship với bảng orders
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'OrderCode', 'order_code');
    }

    /**
     * Relationship với bảng branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'ShopID', 'shopId');
    }

    /**
     * Relationship với bảng users
     */
    public function user()
    {
        return $this->belongsTo(CoreUsers::class, 'IDUser', 'id');
    }

    /**
     * Relationship với bảng status_name
     */
    public function statusName()
    {
        return $this->belongsTo(StatusName::class, 'statusName', 'key');
    }

    /**
     * Relationship với bảng doi_soat_user
     */
    public function doiSoatUser()
    {
        return $this->belongsTo(DoiSoatUser::class, 'IdDoiSoatUser', 'id');
    }

    // check status translate thành tên việt
    public static function formatStatus($statusName)
    {
        $check = $statusName;

        switch ($check) {
            case 'ready_to_pick':
                return 'Mới tạo đơn hàng';
            case 'picking':
                return 'Nhân viên đang lấy hàng';
            case 'cancel':
                return 'Hủy đơn hàng';
            case 'money_collect_picking':
                return 'Đang thu tiền người gửi';
            case 'picked':
                return 'Nhân viên đã lấy hàng';
            case 'storing':
                return 'Hàng đang nằm ở kho';
            case 'transporting':
                return 'Đang luân chuyển hàng';
            case 'sorting':
                return 'Đang phân loại hàng hóa';
            case 'delivering':
                return 'Nhân viên đang giao cho người nhận';
            case 'money_collect_delivering':
                return 'Nhân viên đang thu tiền người nhận';
            case 'delivered':
                return 'Giao hàng thành công';
            case 'delivery_fail':
                return 'Giao hàng thất bại';
            case 'waiting_to_return':
                return 'Đang đợi trả hàng về cho người gửi';
            case 'return':
                return 'Trả hàng';
            case 'return_transporting':
                return 'Đang luân chuyển hàng trả';
            case 'return_sorting':
                return 'Đang phân loại hàng trả';
            case 'returning':
                return 'Nhân viên đang đi trả hàng';
            case 'return_fail':
                return 'Trả hàng thất bại';
            case 'returned':
                return 'Trả hàng thành công';
            case 'exception':
                return 'Đơn hàng ngoại lệ không nằm trong quy trình';
            case 'damage':
                return 'Hàng bị hư hỏng';
            case 'lost':
                return 'Hàng bị mất';
            default:
                return 'Không xác định';
        }
    }
}
