<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportExcelDoiSoat;
use App\Http\Controllers\BaseBackendController;
use App\Imports\ImportExcelDoiSoat;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\DoiSoat;
use App\Models\DoiSoatUser;
use App\Models\Orders;
use App\Models\StatusName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Petstore30\User;
use PhpOffice\PhpSpreadsheet\IOFactory;


class DoiSoatController extends BaseBackendController
{
    //
    public function index(Request $request)
    {
        $data = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->orderBy('lck_doi_soat.id', 'desc')
            ->get(['lck_doi_soat.*', 'lck_orders.*']);

        $user = Auth::guard('backend')->user()->id;

        $data1 = DoiSoatUser::where('user_id', $user)->orderBy('created_at', 'desc')->get();



        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data'] = $data;
        $this->_data['data1'] = $data1;
        return view('backend.doisoat.index', $this->_data);
    }

    public function detail(Request $request, $user_id)
    {
        dd($user_id);
        $data = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->where('IdDoiSoatUser', $user_id)
            ->orderBy('lck_doi_soat.id', 'desc')
            ->get(['lck_doi_soat.*', 'lck_orders.*']);

        $user = Auth::guard('backend')->user()->id;

        $data1 = DoiSoatUser::where('user_id', $user)->get();
        $this->_data['data'] = $data;
        $this->_data['statusNames'] = StatusName::all();
        $this->_data['data1'] = $data1;
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
            $shopId = $item[4];
            $isCheckBrand = Branch::where('shopId', $shopId)->first();
            $userid = Orders::where('order_code', $item[1])->first()->user_id ?? '';
            if ($isCheckBrand) {

                $DoiSoat = DoiSoat::where('OrderCode', $item[1])->first();
                if (!empty($DoiSoat)) {
                    $tongdoisoat = 0;
                    if ($item[8] == 'TRUE') {
                        $tongdoisoat = (int)$item[10] + (int)$item[11] - (int)$item[17];
                    } elseif ($item[8] == 'FALSE') {
                        $tongdoisoat = (int)$item[11] - (int)$item[17];
                    }

                    $params = [
                        'tinhtrangthutienGTB' => $item[8],
                        'tongdoisoat' => $tongdoisoat ?? 0,
                        'userCode' => $userid,
                    ];

                    if (!empty($DoiSoat)) {
                        $DoiSoat->update($params);
                    } else {
                        DoiSoat::create($params);
                    }

                    $idUser = $userid;
                    if (isset($idUserCount[$idUser])) {
                        $idUserCount[$idUser]++;
                        $dataByIdUser[$idUser][] = $item;
                        $tongtienCOD[$idUser] += $item[10];
                        $GTBThutien[$idUser] += $item[16];
                        $thucnhan[$idUser] += $tongdoisoat;
                    } else {
                        $idUserCount[$idUser] = 1;
                        $dataByIdUser[$idUser] = [$item];
                        $tongtienCOD[$idUser] = $item[10];
                        $GTBThutien[$idUser] = $item[16];
                        $thucnhan[$idUser] = $tongdoisoat;
                    }
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


        $query = DoiSoat::join('lck_orders', 'lck_doi_soat.OrderCode', '=', 'lck_orders.order_code')
            ->orderBy('lck_doi_soat.id', 'desc');

        if ($user != 168) {
            $query->where('lck_doi_soat.type', '=', 2)
                ->where('lck_doi_soat.userCode', '=', $user);
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
        $data = DoiSoat::where('type', 2)->get();
        dd($data->toArray());












        $data = DoiSoat::where('type', 1)->get();

        $idUserCount = [];
        $dataByIdUser = [];
        $tongtienCOD = [];
        $GTBThutien = [];
        $thucnhan = [];

        foreach ($data as $item) {
            $order = Orders::where('order_code', $item->OrderCode)->first();
            $userid = $order->user_id ?? null;

            if ($userid !== null) {
                $params = [
                    'doisoat' => 'Đã chuyển COD_' . date('d-m-Y'),
                    'type' => '2',
                    'userCode' => $userid,
                ];
                $item->update($params);
                $tongdoisoat = (int)$item->cod_failed_amount -  (int)$item->tongphi;
                if (isset($idUserCount[$userid])) {
                    $idUserCount[$userid]++;
                    $dataByIdUser[$userid][] = $item;
                    $tongtienCOD[$userid] += $item->CODAmount ?? 0;
                    $GTBThutien[$userid] += $item->cod_failed_amount ?? 0;
                    $thucnhan[$userid] = $tongdoisoat;
                } else {
                    $idUserCount[$userid] = 1;
                    $dataByIdUser[$userid] = [$item];
                    $tongtienCOD[$userid] = $item->CODAmount ?? 0;
                    $GTBThutien[$userid] = $item->cod_failed_amount ?? 0;
                    $thucnhan[$userid] = $tongdoisoat;
                }
            }
        }

        foreach ($idUserCount as $idUser => $count) {
            $aUser = CoreUsers::find($idUser);
            $DoiSoatUser = DoiSoatUser::create([
                'user_id' => $idUser,
                'maphienchuyentien' => 'COD_' . date('d-m-Y').'_'.$aUser->phone,
                'thoigianchuyentien' => date('d-m-Y'),
                'tongtienCOD' => $tongtienCOD[$idUser],
                'GTBThutien' => $GTBThutien[$idUser],
                'thucnhan' => $thucnhan[$idUser],
                'soHDtuongung' => $count,
            ]);
            if (!empty($DoiSoatUser)) {
                $dosoat = DoiSoat::where('userCode', $userid)->get();
                foreach ($dosoat as $doisoatItem) {
                    $doisoatItem->update(['IdDoiSoatUser' => $DoiSoatUser->id]);
                }
            }
        }
        return redirect()->route('backend.doi_soat.index')->with('success', 'Chạy đối soát thành công.');
    }


}
