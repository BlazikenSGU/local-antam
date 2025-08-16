<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportExcelDoiSoat;
use App\Imports\ImportExcelDoiSoat;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\DoiSoat;
use App\Models\DoiSoatUser;
use App\Models\Orders;
use App\Models\StatusName;
use App\Utils\Common as Utils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Petstore30\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;


class DoiSoatController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::guard('backend')->user()->id;



        // Lấy dữ liệu đối soát với eager loading
        $doisoat = DoiSoat::with([
            'order:id,order_code,statusName,payment_method,cod_failed_amount',
            'branch:id,name_show,shopId',
            'user:id,phone',
            'statusName:id,name,key'
        ])
            ->orderBy('id', 'desc')
            ->paginate(50);

        // Lấy dữ liệu đối soát user với eager loading
        $data1 = DoiSoatUser::with([
            'doiSoats' => function ($query) {
                $query->select('id', 'OrderCode', 'IdDoiSoatUser')
                    ->with(['order:id,order_code,statusName,payment_method,cod_failed_amount']);
            }
        ])
            ->where('user_id', $user)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data1'] = $data1;
        $this->_data['doisoat'] = $doisoat;

        return view('backend.doisoat.index', $this->_data);
    }

    public function edit(Request $request, $id)
    {

        $doisoat = DoiSoat::find($id);
        if (!$doisoat) {
            return redirect()->route('backend.doi_soat.index')->with('error', 'Đối soát không tồn tại.');
        }

        return view('backend.doisoat.edit', compact('doisoat'));
    }

    public function update(Request $request, $id)
    {
        $doisoat = DoiSoat::find($id);

        if (!$doisoat) {
            return redirect()->route('backend.doi_soat.index')->with('error', 'Đối soát không tồn tại.');
        }

        if ($doisoat->type == 2 || $doisoat->type == 1 || $doisoat->type == null) {
            $request->validate([
                'cod' => 'nullable|numeric',
                'cod_failed_amount' => 'nullable|numeric',
                'mainservice' => 'nullable|string|max:255',
                'tongphi' => 'nullable|numeric',
                'tongdoisoat' => 'nullable|numeric',
                'tinhtrangthutienGTB' => 'nullable|boolean',
            ]);

            $data = [
                'CODAmount' => $request->input('cod', 0),
                'cod_failed_amount' => $request->input('cod_failed_amount', 0),
                'MainService' => $request->input('mainservice', ''),
                'tongphi' => $request->input('tongphi', 0),
                'tinhtrangthutienGTB' => $request->input('tinhtrangthutienGTB', null),
            ];

            if ($request->input('tinhtrangthutienGTB') == '1') {
                $data['tongdoisoat'] =  $request->input('cod') + $request->input('cod_failed_amount') - $request->input('tongphi');
            } else {
                $data['tongdoisoat'] = $request->input('cod') - $request->input('tongphi');
            }

            $doisoat->update($data);

            return redirect()->route('backend.doi_soat.index')->with('success', 'Cập nhật đối soát thành công.');
        } else {
            return redirect()->route('backend.doi_soat.index')->with('error', 'Không thể cập nhật đối soát này vì trạng thái chuyển COD chưa đúng.');
        }
    }

    public function ajaxData(Request $request)
    {
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
            'page' => $request->get('page') ?? null,
            'limit' => 20,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $data = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->orderBy('lck_doi_soat.id', 'desc')->paginate($params['limit'])->withPath($params['pagin_path']);


        $user = Auth::guard('backend')->user()->id;

        $data1 = DoiSoatUser::where('user_id', $user)->orderBy('created_at', 'desc')->get();


        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data'] = $data;
        $this->_data['data1'] = $data1;
        $start = ($data->currentPage() - 1) * $filter['limit'];


        $this->_data['data_list'] = $data;


        $this->_data['start'] = $start;
        $html = view('backend.doisoat.ajax.loadmoredata', $this->_data)->render();

        return $this->returnResult(['html' => $html, 't' => $params]);
    }

    public function detail(Request $request, $user_id)
    {

        $data = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->where('lck_doi_soat.IdDoiSoatUser', $user_id)
            ->orderBy('lck_doi_soat.id', 'desc')
            ->get(['lck_doi_soat.*', 'lck_orders.*']);

        $user = Auth::guard('backend')->user()->id;

        $data1 = DoiSoatUser::where('user_id', $user)->get();
        $this->_data['data'] = $data;
        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data1'] = $data1;
        $this->_data['idDoisoat'] = $user_id;
        return view('backend.doisoat.detail', $this->_data);
    }

    public function showFormImport()
    {

        return view('backend.doisoat.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

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

            // Khai báo mảng chứa dữ liệu của từng hàng
            $rowData = [];

            // Lặp qua từng cột từ cột A đến cột S
            for ($col = 'A'; $col <= 'S'; $col++) {
                // Lấy giá trị của ô
                $value = $worksheet->getCell($col . $row)->getValue();
                // Thêm giá trị vào mảng $rowData
                $rowData[] = $value;
            }

            // Thêm mảng dữ liệu của hàng vào mảng chứa toàn bộ dữ liệu
            $data[] = $rowData;
        }
        $idUserCount = [];
        $dataByIdUser = [];
        $tongtienCOD = [];
        $GTBThutien = [];
        $thucnhan = [];

        foreach ($data as $index => $item) {
            //$shopId = $item[4];
            //$isCheckBrand = Branch::where('shopId', $shopId)->first();
            $order = Orders::where('order_code', $item[1])->first();
            if (empty($order)) {
                continue;
            }

            $userid = $order->user_id;
            $shopId =  $order->product_type;

            if ($order) {

                $DoiSoat = DoiSoat::where('OrderCode', $item[1])->first();
                if (!empty($DoiSoat)) {
                    $tongphi = $tongdoisoat = 0;
                    if ($order->payment_method == 1) {
                        $tongphi =  (int)$DoiSoat->phigiao1lan +  (int)$DoiSoat->Return +  (int)$DoiSoat->R2S +  (int)$DoiSoat->Insurance +   (int)$DoiSoat->MainService;
                    } else {
                        $tongphi = (int) $DoiSoat->phigiao1lan;
                    }

                    $cod = (int)$DoiSoat->CODAmount;
                    $cod_failed_amount = (int) str_replace(['.', ','], '', $order->cod_failed_amount);
                    if ($item[8] == 'TRUE') {
                        $tongdoisoat =  $cod + (int)$cod_failed_amount - $tongphi; // cod + Tiền GTB  - Tông phí
                    } else {
                        $tongdoisoat = $cod - $tongphi;
                    }

                    $params = [
                        'tinhtrangthutienGTB' => $item[8],
                        'tongphi' => $tongphi,
                        'tongdoisoat' => $tongdoisoat,
                        'userCode' => $userid,
                    ];
                    $DoiSoat->update($params);
                }
            }
        }

        return redirect()->route('backend.doi_soat.index')->with('success', 'Import dữ liệu thành công.');
    }

    public function export(Request $request)
    {
        $user = Auth::guard('backend')->user()->id;
        $date_start = $request->get('date_start');
        $date_end = $request->get('date_end');
        $status = $request->get('status');
        $all = $request->get('status1');
        $cod = $request->get('cod');
        $id_doiSoat = $request->get('id_doiSoat');

        $query = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->orderBy('lck_doi_soat.id', 'desc');

        if ($user != 168) {
            $query->where('lck_doi_soat.type', '=', 2)
                ->where('lck_doi_soat.IdDoiSoatUser', '=', $id_doiSoat);
        }

        if (!empty($date_start) && !empty($date_end)) {
            // Convert date strings to Carbon instances for better date manipulation
            $start = \Carbon\Carbon::parse($date_start)->startOfDay();
            $end = \Carbon\Carbon::parse($date_end)->endOfDay();

            $query->whereBetween('lck_doi_soat.created_at', [$start, $end]);
        }

        if (!empty($cod)) {
            $query->whereIn('lck_doi_soat.type', $cod);
        }


        if (!empty($status)) {
            $query->where('lck_orders.statusName', $status);
        }

        $data = $query->get(['lck_doi_soat.*', 'lck_orders.*']);

        $fileName = 'ĐỐI SOÁT - ' . Carbon::now()->format('d-m-Y H-i') . '.xlsx';

        return Excel::download(new ExportExcelDoiSoat($data), $fileName);
    }

    public function run(Request $request)
    {
        $data = DoiSoat::where('type', 1)->get();

        //        $data = DoiSoat::whereIn('OrderCode', $array)->get();
        $userDatas = [];
        $existingUserIds = []; // Mảng để theo dõi các user_id đã xuất hiện

        foreach ($data as $item) {
            $userId = $item->IDUser;
            if ($userId && !in_array($userId, $existingUserIds)) {
                $userDatas[] = $userId;
                $existingUserIds[] = $userId; // Thêm user_id vào mảng đã xuất hiện
            }
        }

        foreach ($userDatas as $user) {
            // kiêm tra thục nhận của đối soát gần nhất.
            $UserDOiSOAT = DoiSoatUser::where('user_id', $user)->orderBy('id', 'desc')->first();
            $thucnhanTruocky = 0;
            if (!empty($UserDOiSOAT) and $UserDOiSOAT->thucnhan < 0) {
                $thucnhanTruocky = $UserDOiSOAT->thucnhan;
            }
            $DoiSoay_User = DoiSoat::where('type', 1)->where('IDUser', $user);

            $count = $DoiSoay_User->count();
            $tongdoisoat = DoiSoat::where('type', 1)->where('IDUser', $user)->sum('tongdoisoat') + $thucnhanTruocky;
            $GTBThutien = DoiSoat::where('type', 1)->where('IDUser', $user)->where('tinhtrangthutienGTB', '=', 1)->sum('cod_failed_amount');
            $tongtienCOD = DoiSoat::where('type', 1)->where('IDUser', $user)->sum('CODAmount');

            $aUser = CoreUsers::find($user);
            if (!empty($aUser)) {

                $DoiSoatUser = DoiSoatUser::create([
                    'user_id' => $user,
                    'maphienchuyentien' => 'COD_' . date('d-m-Y') . '_' . $aUser->phone,
                    'thoigianchuyentien' => date('d-m-Y'),
                    'tongtienCOD' => $tongtienCOD,
                    'GTBThutien' => $GTBThutien,
                    'thucnhan' => $tongdoisoat,
                    'soHDtuongung' => $count,
                ]);

                $aDoiSoay_User = $DoiSoay_User->get();

                foreach ($aDoiSoay_User as $item) {
                    $item->update([
                        'IdDoiSoatUser' => $DoiSoatUser->id,
                        'doisoat' => 'Đã chuyển COD ' . date('d-m-Y'),
                        'type' => 2,
                    ]);
                }
            }
        }

        return redirect()->route('backend.doi_soat.index')->with('success', 'Chạy đối soát thành công.');
    }

    public function search(Request $request)
    {

        $user = Auth::guard('backend')->user()->id;
        $keyword = $request->get('keyword');

        if ($keyword) {
            $doisoat = Doisoat::where('OrderCode', 'LIKE', '%' . $keyword . '%')
                ->orderBy('id', 'desc')
                ->paginate(50);
        } else {
            $doisoat = DoiSoat::with([
                'order:id,order_code,statusName,payment_method,cod_failed_amount',
                'branch:id,name_show,shopId',
                'user:id,phone',
                'statusName:id,name,key'
            ])
                ->orderBy('id', 'desc')
                ->paginate(50);
        }

        $data1 = DoiSoatUser::with([
            'doiSoats' => function ($query) {
                $query->select('id', 'OrderCode', 'IdDoiSoatUser')
                    ->with(['order:id,order_code,statusName,payment_method,cod_failed_amount']);
            }
        ])
            ->where('user_id', $user)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data1'] = $data1;
        $this->_data['doisoat'] = $doisoat;

        return view('backend.doisoat.index', $this->_data);;
    }
}
