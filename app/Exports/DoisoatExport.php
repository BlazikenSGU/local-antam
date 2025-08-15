<?php

namespace App\Exports;

use App\Models\CoreUsers;
use App\Models\Orders;
use App\Models\DoiSoat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Symfony\Component\Console\Helper\Helper;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class DoisoatExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orderCodes;
    protected $stt = 1;

    public function __construct($orderCodes)
    {
        $this->orderCodes = $orderCodes;
    }


    public function collection()
    {
        return Doisoat::whereIn('OrderCode', $this->orderCodes)->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã đơn đối soát',
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
            'Phí chênh lệch',
            'Phí giao 1 phần',
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
            'Cao',
            'Ngân hàng',
            'Số tài khoản',
            'Chủ tài khoản NH',
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

    // public function columnFormats(): array
    // {
    //     return [
    //         'AG' => NumberFormat::FORMAT_TEXT, // AF là cột số tài khoản
    //     ];
    // }

    public function map($doisoat): array
    {

        $order = Orders::where('order_code', $doisoat->OrderCode)->first();
        $user = CoreUsers::where('id', $doisoat->IDUser)->first();
        $namebank = format_name_bank()[$user->bank_name] ?? $user->bank_name;
        $tongphi = ceil($doisoat->tongphi);

        return [
            $this->stt++,
            $doisoat->OrderCode,
            $doisoat->IDUser,
            $doisoat->client_order_code,
            $doisoat->PartialReturnCode,
            $order->fullname ?? '',
            $order->phone ?? '',
            ($order->address ?: '') . ', ' .
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
            $doisoat->CODAmount ?: '0',
            $order->insurance_value ?: '0',
            $doisoat->MainService ?: '0',
            $doisoat->Insurance ?: '0',
            $doisoat->R2S ?: '0',
            $doisoat->Return ?: '0',
            $doisoat->fee_shopId ?: '0',
            $doisoat->phigiao1lan ?: '0',
            $doisoat->tongphi ? $tongphi : '0',
            $doisoat->cod_failed_amount ?: '0',
            $doisoat && $doisoat->tinhtrangthutienGTB == 1 ? 'Thành công' : '',
            $doisoat ? $doisoat->doisoat : '',
            $doisoat->created_at->format('d-m-Y H:i:s'),
            $doisoat && $doisoat->ngaygiaohoanthanhcong
                ? Carbon::parse($doisoat->ngaygiaohoanthanhcong)->format('d-m-Y H:i:s')
                : '',
            $order->payment_method == 1 ? 'Bên gửi trả phí' : 'Bên nhận trả phí',
            $order->weight,
            $order->width,
            $order->length,
            $order->height,
            $namebank,
            $user->bank_number,
            $user->bank_account,
        ];
    }
}
