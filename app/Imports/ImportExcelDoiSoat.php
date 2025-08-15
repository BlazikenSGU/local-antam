<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\DoiSoat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; // Import WithStartRow


class ImportExcelDoiSoat implements ToModel, WithStartRow
{

//    public function model(array $row)
//    {
//        $shopId = $row[10];
//        $isCheckBrand = Branch::where('shopId', $shopId)->first();
//        if($isCheckBrand) {
//            return new DoiSoat([
//                'CODAmount' => $row[0],
//                'ConvertedWeight' => $row[1],
//                'Insurance' => $row[2],
//                'MainService' => $row[3],
//                'R2S' => $row[4],
//                'Return' => $row[5],
//                'Height' => $row[6],
//                'Length' => $row[7],
//                'OrderCode' => $row[8],
//                'PartialReturnCode' => $row[9],
//                'ShopID' => $row[10],
//                'Status' => $row[11],
//                'Weight' => $row[12],
//                'Width' => $row[13],
//            ]);
//        } else {
//            return false;
//        }
//    }

    public function model(array $row)
    {
        $shopId = $row[10];
        $isCheckBrand = Branch::where('shopId', $shopId)->first();
        if ($isCheckBrand) {
            return new DoiSoat([
                'doisoat' => $row[0],
                'OrderCode' => $row[1],
                'PartialReturnCode' => $row[2],
                'client_order_code' => $row[3],
                'ShopID' => $row[4],
                'IDUser' => $row[5],
                'ngaytaodon' => $row[6],
                'ngaygiaohoanthanhcong' => $row[7],
                'tinhtrangthutienGTB' => $row[8],
                'Status' => $row[9],
                'CODAmount' => $row[10],
                'cod_failed_amount' => $row[11],
                'MainService' => $row[12],
                'R2S' => $row[13],
                'Insurance' => 111,
                'Return' => $row[15],
                'phigiao1lan' => $row[16],
                'tongphi' => $row[17],
                'tongdoisoat' => $row[18],
//                'ConvertedWeight' => $row[1],
//                'Insurance' => $row[2],
//                'MainService' => $row[3],
//                'R2S' => $row[4],
//                'Return' => $row[5],
//                'Height' => $row[6],
//                'Length' => $row[7],
//                'OrderCode' => $row[8],
//                'PartialReturnCode' => $row[9],
//                'ShopID' => $row[10],
//                'Status' => $row[11],
//                'Weight' => $row[12],
//                'Width' => $row[13],
            ]);
        } else {
            return null; // Trả về null để bỏ qua hàng này khi import
        }
    }

    public function startRow(): int // Define startRow method
    {
        return 2; // Skip the first row (header)
    }
//    /**
//    * @param Collection $collection
//    */
//    public function collection(Collection $collection)
//    {
//        //
//        foreach ($collection as $row) {
//            DoiSoat::create([
//                'CODAmount' => $row[0],
//                'ConvertedWeight' => $row[1],
//                'Insurance' => $row[2],
//                'MainService' => $row[3],
//                'R2S' => $row[4],
//                'Return' => $row[5],
//                'Height' => $row[6],
//                'Length' => $row[7],
//                'OrderCode' => $row[8],
//                'PartialReturnCode' => $row[9],
//                'ShopID' => $row[10],
//                'Status' => $row[11],
//                'Weight' => $row[12],
//                'Width' => $row[13],
//            ]);
//        }
//    }
}
