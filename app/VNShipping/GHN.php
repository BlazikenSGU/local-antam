<?php


namespace App\VNShipping;


use App\Models\Branch;
use App\Models\Orders;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use ZipStream\Exception;
use GuzzleHttp\Psr7;

class GHN
{
    protected static $token = '90efe31e-27ed-11f0-a085-4a8e57ff73ff';
    protected static $service_type_id = 2;
    protected static $url_CaGiaoHang = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shift/date';
    protected static $url_TinhPhi = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee';
    protected static $url_TinhPhiChitiet = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/soc';
    protected static $url_XemTruocKhiTaoDon = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/preview';
    protected static $url_TaoDon = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create';
    protected static $url_cancel = 'https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/cancel';
    protected static $url_tao_ticker = 'https://online-gateway.ghn.vn/shiip/public-api/ticket/create';
    protected static $url_chitiet_don = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail';
    protected static $url_capnhapthongtin_don = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/update';
    protected static $url_thay_doi_gia_cod = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/updateCOD';

    protected static $url_tra_hang = 'https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/return';
    protected static $url_giao_hang = 'https://online-gateway.ghn.vn/shiip/public-api/v2/switch-status/storing';
    protected static $client_order_code = 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail-by-client-code';




    public static function getApiUrl()
    {
        return self::$url_TaoDon;
    }

    public static function getToken()
    {
        return self::$token;
    }

    public static function getShiftDate()
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'Content-Type' => 'application/json',
            ];
            $response = $client->post(self::$url_CaGiaoHang, [
                'headers' => $headers,
            ]);
            $responseData = json_decode($response->getBody(), true);
            if ($responseData['code'] == 200) {
                $data = [
                    'data' => $responseData['data'],
                    'r' => 0,
                    'msg' => '',
                ];
                return $data;
            } else {
                $data = [
                    'data' => '',
                    'r' => 1,
                    'msg' => $responseData['message'],
                ];
                return $data;
            }
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }

    public static function Detail($params)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'Content-Type' => 'application/json',
            ];
            $body = [
                "order_code"       => $params,
            ];
            $response = $client->post(self::$url_chitiet_don, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData['data'],
                'r' => 0,
                'msg' => '',
            ];

            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }

    public static function GetFee($params)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'shopId' => $params['shopId'],
            ];
            $body = [
                "service_type_id"       => self::$service_type_id,
                "from_district_id"      => intval($params['from_district_id']),
                "to_district_id"        => intval($params['to_district_id']),
                "to_ward_code"          => $params['to_ward_code'],
                "height"                => 0,
                "length"                => 0,
                "weight"                => intval($params['weight']),
                "width"                 => 0,
                "insurance_value"       => intval(str_replace(',', '', $params['insurance_value'])),
                "coupon"                => null
            ];
            $response = $client->post(self::$url_TinhPhi, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData['data'],
                'r' => 0,
                'msg' => '',
            ];

            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }
    public static function GetDetailFee($params)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'shopId' => $params['shopId'],
            ];
            $body = [
                "order_code"       => $params['order_code'],

            ];
            $response = $client->post(self::$url_TinhPhiChitiet, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData['data'],
                'r' => 0,
                'msg' => '',
            ];

            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }

    public static function GetPreview($data, $externalItems)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'shopId' => $data['product_type'],
                'Content-Type' => 'application/json'
            ];


            $body = [
                "payment_type_id" => (int)$data['payment_method'],
                "note" => $data['note'],
                "required_note" => $data['required_note'],
                "return_phone" => $data['phone'],
                "return_address" => $data['address'],
                "return_district_id" => null,
                "return_ward_code" => "",
                "client_order_code" => "",
                "to_name" =>  $data['to_name'],
                "to_phone" => $data['to_phone'],
                "to_address" => $data['to_address'],
                "to_ward_code" => $data['to_ward'],
                "to_district_id" => (int)$data['to_district'],
                "cod_amount" => 0,
                "content" => null,
                "weight" => (int)$data['weight'],
                "length" => (int)$data['length'],
                "width" => (int)$data['width'],
                "height" => (int)$data['height'],
                "pick_station_id" => 0,
                "insurance_value" => (int)str_replace(',', '', $data['insurance_value']),
                "service_id" => 0,
                "service_type_id" => 2,
                "coupon" => null,
                "items" => $externalItems
            ];

            $response = $client->post(self::$url_XemTruocKhiTaoDon, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData['data'],
                'r' => 0,
                'msg' => '',
            ];
            return $data;
        } catch (\Exception $e) {
            $response = $e->getResponse();
            $errorMessage = json_decode($response->getBody()->getContents(), true)['message'];

            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $errorMessage,
            ];
            return $data;
        }
    }
    public static function Order($data, $externalItems)
    {
        try {

            $client = new Client();

            $headers = [
                'token' => self::$token,
                'shopId' => $data['product_type'],
                'Content-Type' => 'application/json'
            ];


            $body = [
                "payment_type_id" => (int)$data['payment_method'],
                "note" => $data['note'],
                "required_note" =>  $data['required_note'],

                "return_phone" => $data['return_phone'],
                "return_address" => $data['return_address'],
                "return_district_id" => $data['return_district'],
                "return_ward_code" => $data['return_ward'],
                "client_order_code" =>  $data['order_code_custom'],
                // Ngươi gui
                "from_name" =>  $data['fullname'],
                "from_phone" => $data['phone'],
                "from_address" => $data['address'],
                "from_ward_name" => $data['ward_name'],
                "from_district_name" => $data['district_name'],
                "from_province_name" =>  $data['province_name'],
                // Nguoi nhận
                "to_name" => $data['to_name'],
                "to_phone" => $data['to_phone'],
                "to_address" => $data['to_address'],
                "to_ward_name" => $data['to_ward_name'],
                "to_district_name" => $data['to_district_name'],
                "to_province_name" => $data['to_province_name'],

                "cod_amount" => (int) str_replace(['.', ','], '', $data['cod_amount']) + (int) str_replace(['.', ','], '', $data['payment_fee']),
                "content" => null,
                "weight" => (int)$data['weight'],
                "length" => (int)$data['length'],
                "width" => (int)$data['width'],
                "height" => (int)$data['height'],
                "pick_station_id" => 0,
                "insurance_value" => (int) str_replace(['.', ','], '', $data['insurance_value']),
                "service_id" => 0,
                "service_type_id" => 2,
                "coupon" => null,
                "code" => $data['order_code_custom'],
                "items" => $externalItems,
                "cod_failed_amount" => (int) str_replace(['.', ','], '', $data['cod_failed_amount']),

            ];

            //            return $body;

            $response = $client->post(self::$url_TaoDon, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData,
                'i' => $body,
                'r' => 0,
                'msg' => '',
            ];
            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }

    public static function updateOrder($data)
    {
        try {

            $client = new Client();
            $headers = [
                'token' => self::$token,
                'shopId' => $data['shopId'],
                'Content-Type' => 'application/json'
            ];

            $body = $data['body'];


            $response = $client->post(self::$url_capnhapthongtin_don, [
                'headers' => $headers,
                'json' => $body
            ]);

            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData,
                'r' => 0,
                'msg' => '',
            ];
            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }
    public static function ChangeCOD($data)
    {
        try {

            $client = new Client();
            $headers = [
                'token' => self::$token,
                'shopId' => $data['product_type'],
                'Content-Type' => 'application/json'
            ];


            $body = [
                "order_code" => $data['order_code'],
                "cod_amount" => (int) str_replace(['.', ','], '', $data['cod_amount']),

            ];



            $response = $client->post(self::$url_thay_doi_gia_cod, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData,
                'r' => 0,
                'msg' => '',
            ];
            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }
    public static function CancelOrder($data)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'Content-Type' => 'application/json',
                'ShopId' => $data['product_type'],
            ];
            $body = [
                "order_codes" => [$data['order_code']]
            ];
            $response = $client->post(self::$url_cancel, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            if ($responseData['code'] == 200) {
                $data = [
                    'data' => $responseData['data'],
                    'r' => 0,
                    'msg' => '',
                ];
                return $data;
            } else {
                $data = [
                    'data' => '',
                    'r' => 1,
                    'msg' => $responseData['message'],
                ];
                return $data;
            }
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }
    public static function ReturnOrder($data)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'Content-Type' => 'application/json',
                'ShopId' => $data['product_type'],
            ];
            $body = [
                "order_codes" => [$data['order_code']]
            ];
            $response = $client->post(self::$url_tra_hang, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            if ($responseData['code'] == 200) {
                $data = [
                    'data' => $responseData['data'],
                    'r' => 0,
                    'msg' => '',
                ];
                return $data;
            } else {
                $data = [
                    'data' => '',
                    'r' => 1,
                    'msg' => $responseData['message'],
                ];
                return $data;
            }
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }


    public static function StoringOrder($data)
    {
        try {
            $client = new Client();
            $headers = [
                'token' => self::$token,
                'Content-Type' => 'application/json',
                'ShopId' => $data['product_type'],
            ];
            $body = [
                "order_codes" => [$data['order_code']]
            ];
            $response = $client->post(self::$url_giao_hang, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            if ($responseData['code'] == 200) {
                $data = [
                    'data' => $responseData['data'],
                    'r' => 0,
                    'msg' => '',
                ];
                return $data;
            } else {
                $data = [
                    'data' => '',
                    'r' => 1,
                    'msg' => $responseData['message'],
                ];
                return $data;
            }
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }

    public static function CraeteTicket($data)
    {
        $client = new Client();

        try {
            // Gửi yêu cầu POST tới API của GHN
            $response = $client->request('POST', self::$url_tao_ticker, [
                'headers' => [
                    'Token' => self::$token,
                ],
                'multipart' => [
                    [
                        'name' => 'c_email',
                        'contents' => 'cskh@ghn.vn',
                    ],
                    [
                        'name' => 'order_code',
                        'contents' => $data['order_code'],
                    ],
                    [
                        'name' => 'category',
                        'contents' => $data['type'],
                    ],
                    [
                        'name' => 'description',
                        'contents' => $data['description'],
                    ],
                ],
            ]);

            // Xử lý phản hồi từ API
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // Trả về phản hồi
            return $body;
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public static function ClientOrderCode($data)
    {
        $client = new Client();

        try {

            $client = new Client();
            $headers = [
                'token' => self::$token,
            ];


            $body = [
                "client_order_code" => $data,
            ];



            $response = $client->post(self::$client_order_code, [
                'headers' => $headers,
                'json' => $body
            ]);
            $responseData = json_decode($response->getBody(), true);

            $data = [
                'data' => $responseData,
                'r' => 0,
                'msg' => '',
            ];
            return $data;
        } catch (\Exception $e) {
            $data = [
                'data' => '',
                'r' => 1,
                'msg' => $e->getMessage(),
            ];
            return $data;
        }
    }
}
