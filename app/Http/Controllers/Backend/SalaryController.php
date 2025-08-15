<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\CoreUsers;
use App\Models\Project;
use App\Models\Salary;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalaryController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản lý dự án',
        'subtitle' => 'Quản lý dự án',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'fullname' => null,
            'phone'    => null,
            'email'    => null,
            'limit'    => config('constants.item_per_page_admin'),
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = Salary::get_by_where_backend($params);
        $this->_data['list_data'] = $data;
        $this->_data['filter'] = $filter;
        return view('backend.salary.index', $this->_data);
    }

    public function export(Request $request)
    {
        if ($request->ids) {
            $ids = !is_array($request->get('ids')) ? explode(',', $request->get('ids')) : $request->get('ids');
            $salary = Salary::whereIn('id', $ids)->get();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $styleArray = array(
                'font'      => array(
                    'bold' => true,
                    'size' => 16,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders'   => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color'       => array('rgb' => '333333'),
                    ),
                ),
            );
            $styleName = array(
                'font' => array(
                    'bold' => true,
                    'size' => 20,
                ),
            );
            $today = date('d-m-Y');

            $spreadsheet->getActiveSheet()->getStyle('B1:I1')->applyFromArray($styleArray)->getFont()->getColor()
                ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(16);
            $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(24);
            $spreadsheet->getDefaultStyle()->getFont()->setSize(16);
            $sheet->setCellValue('B1', '#');
            $sheet->setCellValue('C1', 'Họ&Tên');
            $sheet->setCellValue('D1', 'Số điện thoại');
            $sheet->setCellValue('E1', 'Email');
            $sheet->setCellValue('F1', 'Nội dung');
            $sheet->setCellValue('G1', 'Thời gian');
            $sheet->setCellValue('H1', 'Ngân hàng(Ngân hàng, Họ&Tên, STK)');
            $sheet->setCellValue('I1', 'Số tiền');
            $rows = 2;

            foreach ($salary as $k => $v) {
                $json = json_decode($v->bank, TRUE);
                if (!empty($json) && is_array($json)) {
                    $b = \App\Models\Banks::findOrFail($json['bank_id']);
                    $bank = 'Ngân hàng: ' . $b['name'] . ', Họ&Tên: ' . $json['fullname'] . ', STK: ' . $json['bank_account_number'];
                    if (!empty($json)) {
                        $sheet->setCellValue('H' . $rows, $bank);
                    }
                }
                $sheet->setCellValue('B' . $rows, $v->id);
                $sheet->setCellValue('C' . $rows, $v->fullname);
                $sheet->setCellValue('D' . $rows, $v->phone);
                $sheet->setCellValue('E' . $rows, $v->email);
                $sheet->setCellValue('F' . $rows, $v->title);
                $sheet->setCellValue('G' . $rows, $v->created_at);
                $sheet->setCellValue('I' . $rows, number_format($v->salary) . 'VND');
                $rows++;
            }
            $writer = new Xlsx($spreadsheet);
            $filename = 'LỊCH SỬ THANH TOÁN : ' . date('d-m-Y_H-i-s') . '.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
        return back();
    }
    public function delete(Request $request, $id)
    {
        try {
            $salary = Salary::find($id);

            if (empty($salary)) {
                $request->session()->flash('msg', ['danger', 'Giao dịch không tồn tại']);
            }
            $user = CoreUsers::find($salary->user_id);
            $user->balance = $user->balance - $salary->salary;
            $user->save();
            $salary->delete();

            $request->session()->flash('msg', ['info', 'Đã hoàn tác giao dịch']);
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }

        return redirect($this->_ref ? $this->_ref : Route('backend.salary.index'));
    }

}
