<?php

namespace App\Exports;

use App\Models\Orders;
use App\Models\DoiSoat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orderCodes;
    protected $stt = 1;

    public function __construct($orderCodes)
    {
        $this->orderCodes = $orderCodes;
    }

    public function collection()
    {
        return Orders::whereIn('order_code', $this->orderCodes)->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã đơn hàng',
            'Mã tài khoản',
            'Mã đơn hàng riêng',
            'Mã giao 1 phần',
            'Người gửi',
            'SDT gửi',
            'Địa chỉ gửi',
            'Người nhận',
            'SDT nhận',
            'Địa chỉ nhận',
            'Trạng thái',
            'COD',
            'Gía trị khai giá',
            'Phí giao hàng',
            'Phí khai giá',
            'Phí giao lại',
            'Phí hoàn hàng',
            'Phí giao 1 phần',
            'Phí chênh lệch',
            'Tổng phí',
            'Tiền GTB - Thu tiền',
            'Trạng thái GTB',
            'Ngày chuyển tiền',
            'Ngày tạo đơn',
            'Ngày giao/ hoàn hàng thành công',
            'Tùy chọn thanh toán',
            'Khối lượng',
            'Rộng',
            'Dài',
            'Cao'
        ];
    }

    public function convertStatus($statusName)
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

    public function map($order): array
    {

        $doisoat = DoiSoat::where('OrderCode', $order->order_code)->first();

        return [
            $this->stt++,
            $order->order_code,
            $order->user_id,
            $order->order_code_custom,
            $order->PartialReturnCode,
            $order->fullname,
            $order->phone,
            $order->address . ', ' .
                $order->ward_name . ', ' .
                $order->district_name . ', ' .
                $order->province_name,
            $order->to_name,
            $order->to_phone,
            $order->to_address . ', ' .
                $order->to_ward_name . ', ' .
                $order->to_district_name . ', ' .
                $order->to_province_name,
            $this->convertStatus($order->statusName),
            $order->cod_amount,
            $order->insurance_value,
            $order->main_service ?: '0',
            $order->insurance_fee ?: '0',
            $order->R2S ?: '0',
            $order->Return ?: '0',
            $doisoat->phigiao1lan ?: '0',
            $order->fee_shopId ?: '0',
            $doisoat ? ceil($doisoat->tongphi) : ($order->total_fee ?: '0'),
            $order->cod_failed_amount ?: '0',
            $doisoat && $doisoat->tinhtrangthutienGTB == 1 ? 'Thành công' : '',
            $doisoat ? $doisoat->doisoat : '',
            $order->created_at->format('d-m-Y H:i:s'),
            $doisoat && $doisoat->ngaygiaohoanthanhcong
                ? Carbon::parse($doisoat->ngaygiaohoanthanhcong)->format('d-m-Y H:i:s')
                : '',
            $order->payment_method == 1 ? 'Bên gửi trả phí' : 'Bên nhận trả phí',
            $order->weight,
            $order->width,
            $order->length,
            $order->height
        ];
    }
}
