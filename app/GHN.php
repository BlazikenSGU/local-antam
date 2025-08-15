<?php


namespace App\VNShipping;


use App\Models\Branch;
use App\Models\Orders;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use ZipStream\Exception;
use GuzzleHttp\Psr7;

// class GHN
// {
//     protected static $token = '2d4752d2-09a2-11f0-94b6-be01e07a48b5';
//     // protected static $shopId = '3309318';
//     protected static $service_type_id = 2; // GIAO HÀNG THUONG MẠI
//     protected static $url_CaGiaoHang = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shift/date';
//     protected static $url_TinhPhi = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
//     protected static $url_XemTruocKhiTaoDon = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/preview';
//     protected static $url_TaoDon = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create';
//     protected static $url_cancel = 'https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel';
//     protected static $url_tao_ticker = 'https://online-gateway.ghn.vn/shiip/public-api/ticket/create';

//     public static function getShiftDate()
//     {
//         try {
//             $client = new Client();
//             $headers = [
//                 'token' => self::$token,
//                 'Content-Type' => 'application/json',
//             ];
//             $response = $client->post(self::$url_CaGiaoHang, [
//                 'headers' => $headers,
//             ]);
//             $responseData = json_decode($response->getBody(), true);
//             if ($responseData['code'] == 200) {
//                 $data = [
//                     'data' => $responseData['data'],
//                     'r' => 0,
//                     'msg' => '',
//                 ];
//                 return $data;
//             } else {
//                 $data = [
//                     'data' => '',
//                     'r' => 1,
//                     'msg' => $responseData['message'],
//                 ];
//                 return $data;
//             }
//         } catch (\Exception $e) {
//             $data = [
//                 'data' => '',
//                 'r' => 1,
//                 'msg' => $e->getMessage(),
//             ];
//             return $data;
//         }
//     }

//     public static function GetFee($params)
//     {
//         try {
//             $client = new Client();
//             $headers = [
//                 'token' => self::$token,
//                 'shopId' => $params['shopId'],
//             ];
//             $body = [
//                 "service_type_id"       => self::$service_type_id,
//                 "from_district_id"      => intval($params['from_district_id']),
//                 "to_district_id"        => intval($params['to_district_id']),
//                 "to_ward_code"          => $params['to_ward_code'],
//                 "height"                => 0,
//                 "length"                => 0,
//                 "weight"                => intval($params['weight']),
//                 "width"                 => 0,
//                 "insurance_value"       => intval( str_replace(',', '', $params['insurance_value'])),
//                 "coupon"                => null
//             ];
//             $response = $client->post(self::$url_TinhPhi, [
//                 'headers' => $headers,
//                 'json' => $body
//             ]);
//             $responseData = json_decode($response->getBody(), true);

//             $data = [
//                 'data' => $responseData['data']['total'],
//                 'r' => 0,
//                 'msg' => '',
//             ];

//             return $data;
//         } catch (\Exception $e) {
//             $data = [
//                 'data' => '',
//                 'r' => 1,
//                 'msg' => $e->getMessage(),
//             ];
//             return $data;
//         }
//     }

//     public static function GetPreview($data, $externalItems)
//     {
//         try {
//             $client = new Client();
//             $headers = [
//                 'token' => self::$token,
//                 'shopId' => $data['product_type'],
//                 'Content-Type' => 'application/json'
//             ];


//             $body = [
//                 "payment_type_id"=> (int)$data['payment_method'],
//                 "note"=> $data['note'],
//                 "required_note"=> "KHONGCHOXEMHANG",
//                 "return_phone"=> $data['phone'],
//                 "return_address"=> $data['address'],
//                 "return_district_id"=> null,
//                 "return_ward_code"=> "",
//                 "client_order_code"=> "",
//                 "to_name"=>  $data['to_name'],
//                 "to_phone"=> $data['to_phone'],
//                 "to_address"=> $data['to_address'],
//                 "to_ward_code"=> $data['to_ward'],
//                 "to_district_id"=> (int)$data['to_district'],
//                 "cod_amount"=> 0,
//                 "content"=> null,
//                 "weight"=> (int)$data['weight'],
//                 "length"=> (int)$data['length'],
//                 "width"=> (int)$data['width'],
//                 "height"=> (int)$data['height'],
//                 "pick_station_id"=> 0,
//                 "insurance_value"=> (int)str_replace(',', '', $data['insurance_value']),
//                 "service_id"=> 0,
//                 "service_type_id"=> 2,
//                 "coupon"=> null,
//                 "items" => $externalItems
//             ];

//             $response = $client->post(self::$url_XemTruocKhiTaoDon, [
//                 'headers' => $headers,
//                 'json' => $body
//             ]);
//             $responseData = json_decode($response->getBody(), true);

//             $data = [
//                 'data' => $responseData['data'],
//                 'r' => 0,
//                 'msg' => '',
//             ];
//             return $data;

//         } catch (\Exception $e) {
//             $data = [
//                 'data' => '',
//                 'r' => 1,
//                 'msg' => $e->getMessage(),
//             ];
//             return $data;
//         }

//     }
//     public static function Order($data, $externalItems)
//     {
//         try {

//             $client = new Client();
//             $headers = [
//                 'token' => self::$token,
//                 'shopId' => $data['product_type'],
//                 'Content-Type' => 'application/json'
//             ];


//             $body = [
//                 "payment_type_id"=> (int)$data['payment_method'],
//                 "note"=> $data['note'],
//                 "required_note"=> "KHONGCHOXEMHANG",
//                 "return_phone"=> $data['phone'],
//                 "return_address"=> $data['address'],
//                 "return_district_id"=> null,
//                 "return_ward_code"=> "",
//                 "client_order_code"=> "",
//                 // Ngươi gui
//                 "from_name"=>  Auth()->guard('backend')->user()->fullname,
//                 "from_phone"=> Auth()->guard('backend')->user()->phone,
//                 "from_address"=> Auth()->guard('backend')->user()->address,
//                 "from_ward_name"=> Auth()->guard('backend')->user()->ward_name,
//                 "from_district_name"=> Auth()->guard('backend')->user()->district_name,
//                 "from_province_name"=>  Auth()->guard('backend')->user()->province_name,
//                 // Nguoi nhận
//                 "to_name"=> $data['to_name'],
//                 "to_phone"=> $data['to_phone'],
//                 "to_address"=> $data['to_address'],
//                 "to_ward_name"=> $data['to_ward_name'],
//                 "to_district_name"=> $data['to_district_name'],
//                 "to_province_name"=> $data['to_province_name'],

//                 "cod_amount"=> (int)$data['cod_amount'] ?? 0,
//                 "content"=> null,
//                 "weight"=> (int)$data['weight'],
//                 "length"=> (int)$data['length'],
//                 "width"=> (int)$data['width'],
//                 "height"=> (int)$data['height'],
//                 "pick_station_id"=> 0,
//                 "insurance_value"=> (int) str_replace(',', '', $data['insurance_value']),
//                 "service_id"=> 0,
//                 "service_type_id"=> 2,
//                 "coupon"=> null,
//                 "items" => $externalItems,
//                 "cod_failed_amount" => (int) str_replace(',', '', $data['cod_failed_amount']),

//             ];

//             $response = $client->post(self::$url_TaoDon, [
//                 'headers' => $headers,
//                 'json' => $body
//             ]);
//             $responseData = json_decode($response->getBody(), true);

//             $data = [
//                 'data' => $responseData,
//                 'r' => 0,
//                 'msg' => '',
//             ];
//             return $data;

//         } catch (\Exception $e) {
//             $data = [
//                 'data' => '',
//                 'r' => 1,
//                 'msg' => $e->getMessage(),
//             ];
//             return $data;
//         }

//     }

//     public static function CancelOrder($data)
//     {
//         try {
//             $client = new Client();
//             $headers = [
//                 'token' => self::$token,
//                 'Content-Type' => 'application/json',
//                 'ShopId' => $data['product_type'],
//             ];
//             $body = [
//                 "order_codes"=> [$data['order_code']]
//                 ];
//             $response = $client->post(self::$url_cancel, [
//                 'headers' => $headers,
//                 'json' => $body
//             ]);
//             $responseData = json_decode($response->getBody(), true);

//             if ($responseData['code'] == 200) {
//                 $data = [
//                     'data' => $responseData['data'],
//                     'r' => 0,
//                     'msg' => '',
//                 ];
//                 return $data;
//             } else {
//                 $data = [
//                     'data' => '',
//                     'r' => 1,
//                     'msg' => $responseData['message'],
//                 ];
//                 return $data;
//             }
//         } catch (\Exception $e) {
//             $data = [
//                 'data' => '',
//                 'r' => 1,
//                 'msg' => $e->getMessage(),
//             ];
//             return $data;
//         }
//     }

//     public static function CraeteTicket($data)
//     {
//         $client = new Client();

//         try {
//             $response = $client->post('https://online-gateway.ghn.vn/shiip/public-api/ticket/create', [
//                 'headers' => [
//                     'Token' => self::$token,
//                     'Content-Type' => 'application/json',
//                 ],
//                 'multipart' => [
//                     'c_email' => 'cskh@ghn.vn',
//                     'order_code' => 'G86RRV3P',
//                     'category' => 'Tư vấn',
//                     'description' => 'Tạo yêu cầu test'
//                 ]
//             ]);

//             return $response->getBody()->getContents();
//         } catch (RequestException $e) {
//             if ($e->hasResponse()) {
//                 $response = $e->getResponse();
//                 return $response->getBody()->getContents();
//             } else {
//                 return $e->getMessage();
//             }
//         }
//     }
// }
