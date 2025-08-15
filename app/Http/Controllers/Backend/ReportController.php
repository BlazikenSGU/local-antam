<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Banner;
use App\Models\DoiSoat;
use App\Models\DoiSoatUser;
use App\Models\Files;
use App\Models\Orders;
use App\Utils\Common as Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportController extends BaseBackendController
{
    protected $_data = array(
        'title' => 'Báo cáo - Live',
        'subtitle' => 'Báo cáo - Live',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->_data['now'] = Carbon::now();
        $this->_data['yesterday'] = Carbon::yesterday();
        $this->_data['dayBeforeYesterday'] = Carbon::now()->subDays(2);

        $this->_data['today'] = Orders::whereDate('created_at', Carbon::today())->count();
        $this->_data['yesterdayCount'] = Orders::whereDate('created_at', Carbon::yesterday())->count();
        $this->_data['dayBeforeYesterdayCount'] = Orders::whereDate('created_at', Carbon::now()->subDays(2))->count();

        return view('backend.report.index', $this->_data);
    }

    public function list(Request $request)
    {
        $key = $request->get('key');
        if ($key == null) {
            return redirect()->back();
        }

        $filter = $params = array_merge(array(
            'order_code' => null,
            'fullname' => null,
            'email' => null,
            'phone' => null,
            'status' => null,
            'user_id' => Auth()->guard('backend')->user()->id ?? null,
            'change_branch' => null,
            'working_date_from' => null,
            'working_date_to' => null,
            'key' => null,
            'date' => null,
            'limit' => config('constants.item_per_page_admin'),
        ), $request->all());

        $objModel = new Orders();

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['date'] = date('Y-m-d');

        $data_list = $objModel->get_by_where($params);

        $start = ($data_list->currentPage() - 1) * $filter['limit'];

        $this->_data['data_list'] = $data_list;


        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        return view('backend.report.list', $this->_data);
    }

    public function amount(Request $request)
    {
        $status = $request->get('status');
        $dateNow = today()->toDateString();
        $userId = Auth()->guard('backend')->user()->id;

        $data = [];

        switch ($status) {
            case 1:
                $statusName = 'picked';
                break;
            case 2:
                $statusName = 'delivery_fail';
                break;
            case 3:
                $statusName = 'delivered';
                break;
            case 4:
                $statusName = 'waiting_to_return';
                break;
            case 5:
                $statusName = 'returned';
                break;
            case 6:
                $statusName = 'damage';
                break;
            default:
                // Xử lý khi $status không khớp với bất kỳ trường hợp nào
                break;
        }

        if ($status) {
            $data = Orders::where('statusName', $statusName)
                ->where('user_id', $userId)
                ->whereDate('created_at', $dateNow)
                ->get();
        }

        $this->_data['data'] = $data;

        $this->_data['status'] = Orders::$Keystatus;



        return view('backend.report.amount', $this->_data);
    }

    public function ChangeFile(Request $request)
    {
        try {
            $file = $request->file('file');

            // Kiểm tra phần mở rộng của tệp
            $extension = $file->getClientOriginalExtension();


            // Di chuyển tệp vào thư mục lưu trữ
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads'), $fileName);

            // Đường dẫn đến tệp Excel
            $filePath = public_path('storage/uploads/' . $fileName);

            // Đọc nội dung của tệp Excel
            $spreadsheet = IOFactory::load($filePath);

            // Lấy sheet đầu tiên từ tệp Excel
            $worksheet = $spreadsheet->getActiveSheet();

            // Lấy số hàng và số cột của sheet
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();


            $data = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                // Kiểm tra xem hàng đó có dữ liệu không
                $value = $worksheet->getCell('A' . $row)->getValue();

                // Nếu hàng không có dữ liệu, thoát khỏi vòng lặp
                if (empty($value)) {
                    break;
                }

                // Khai báo mảng chứa dữ liệu của từng hàng
                $rowData = [];

                // Lặp qua từng cột từ cột A đến cột S
                for ($col = 'A'; $col <= 'F'; $col++) {
                    // Lấy giá trị của ô
                    $value = $worksheet->getCell($col . $row)->getValue();
                    // Thêm giá trị vào mảng $rowData
                    $rowData[] = $value;
                }

                // Thêm mảng dữ liệu của hàng vào mảng chứa toàn bộ dữ liệu
                $data[] = $rowData;
            }

            foreach ($data as $index => $item) {
                $order = Orders::where('order_code', $item[0])->first();

                if ($order) {
                    $order->statusName = $item[1];
                    $order->cod_collect_date = $item[2];
                    $order->cod_transfer_date = $item[3];
                    $order->finish_date = $item[4];
                    $order->cod_failed_collect_date = $item[5];
                    $order->created_at = date('Y-m-d H:i:s');
                    $order->save();
                }
            }
            $request->session()->flash('msg', ['info', 'Cập nhập thành công!']);
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!' . $e->getMessage()]);
        }
        return redirect()->back();
    }
}
