<?php


namespace App\VNShipping;


use App\Models\Branch;
use GuzzleHttp\Client;


class Ahamove
{


    public function __construct($config = []){
//        $this->api_key = 'ca5983394e933332842e1e0295db62c15a11d63a';
        $this->api_key = '2b1d196b77e6ad0671913bb6ed3abc93ee7391cd';
//        $this->api_url = 'apistg';
        $this->api_url = 'api';
    }

    public function getToken($id) {
        $branch = Branch::find($id);

        $client = new Client();

        $response = $client->post('https://'.$this->api_url.'.ahamove.com/v1/partner/register_account', [
            'form_params' => [
                'api_key' => $this->api_key,
                'mobile' => $branch->phone,
                'name' => $branch->name,
                'address' => $branch->address,
                'lat' => $branch->o_lat,
                'lng' => $branch->o_long,
            ],
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode === 200) {
            $accessToken = json_decode($response->getBody(), true)['token'];
            return $accessToken;
        } else {
            return 'Không thể láy token';
        }

    }
    public function getFee($params)
    {
        $token = $this->getToken($params['id']);
        $jsonData = json_encode($params['data']);
        $client = new Client();

        try {
            $response = $client->post('https://'.$this->api_url.'.ahamove.com/v1/order/estimated_fee', [
                'form_params' => [
                    'token' => $token,
                    'order_time' => 0,
                    'path' => $jsonData,
                    'service_id' => 'SGN-EXPRESS',
                    'requests' => [],
                    'promo_code' => 'ATAI10',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $responseData = json_decode($response->getBody(), true);
                //$totalFee = $responseData['total_fee'];
                return $responseData;
            } else {
                throw new \Exception('Không thể lấy phí vận chuyển. Mã trạng thái: ' . $statusCode);
            }
        } catch (\Exception $e) {
            throw new \Exception('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function createdOrder($params)
    {
        $token = $this->getToken($params['id']);
        $jsonData = json_encode($params['data']);
        $jsonItem = json_encode($params['items']);

        //return $token;
        $client = new Client();

        try {
            $response = $client->post('https://'.$this->api_url.'.ahamove.com/v1/order/create', [
                'form_params' => [
                    'token' => $token,
                    'order_time' => 0,
                    'path' => $jsonData,
                    'service_id' => 'SGN-EXPRESS',
                    'requests' => [],
                    'payment_method' => $params['payment_method'],
                    'promo_code' => 'ATAI10',
                    'idle_until' => 0,
                    'items' => $jsonItem,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $responseData = json_decode($response->getBody(), true);
                return $responseData;
            } else {
                if (isset($responseData['code']) && $responseData['code'] === 'NOT_ENOUGH_CREDIT'){
                    $responseData = json_decode($response->getBody(), true);
                    return $responseData;
                }
                throw new \Exception('Không thể tạo đơn hàng Ahamove. Mã trạng thái: ' . $statusCode);
            }
        } catch (\Exception $e) {
            throw new \Exception('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function cancelOrder($params)
    {
        $token = $this->getToken($params['id']);


        $client = new Client();

        try {
            $response = $client->post('https://'.$this->api_url.'.ahamove.com/v1/order/cancel', [
                'form_params' => [
                    'token' => $token,
                    'order_id' => $params['order_id'],
                    'comment' => 'Đặt hàng nhầm.',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $responseData = json_decode($response->getBody(), true);
                //$totalFee = $responseData['total_fee'];
                return $responseData;
            } else {
                return 'Không thể hủy đơn hàng Ahamove. Mã trạng thái: ' . $statusCode;
            }
        } catch (\Exception $e) {
            return 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }

}
