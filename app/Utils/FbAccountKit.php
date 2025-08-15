<?php
/**
 * User: LuanNT
 * Date: 30/05/2018
 * Time: 2:06 CH
 */

namespace App\Utils;

class FbAccountKit
{
    public static function verify($ack_token)
    {
        $app_id = config('constants.account_kit_app_id');
        $secret = config('constants.account_kit_app_secret');
        $version = config('constants.account_kit_version');

        try {
            // Exchange authorization code for access token
            $token_exchange_url = 'https://graph.accountkit.com/' . $version . '/access_token?' .
                                  'grant_type=authorization_code' .
                                  '&code=' . $ack_token .
                                  "&access_token=AA|$app_id|$secret";

            $data = self::doCurl($token_exchange_url);

            if (isset($data['error'])) {
                return [
                    'e'   => 1,
                    'msg' => $data['error']['message']
                ];
            }

            $user_id = $data['id'];
            $user_access_token = $data['access_token'];
            $refresh_interval = $data['token_refresh_interval_sec'];

            // Get Account Kit information
            $me_endpoint_url = 'https://graph.accountkit.com/' . $version . '/me?access_token=' . $user_access_token;

            $data = self::doCurl($me_endpoint_url);

            if (isset($data['error'])) {
                return [
                    'e'   => 2,
                    'msg' => $data['error']['message']
                ];
            }
            if (isset($data['phone']['national_number'])) {
                return [
                    'e'            => 0,
                    'phone_number' => '0' . $data['phone']['national_number']
                ];
            }

            return [
                'e'   => -99,
                'msg' => 'FB API error'
            ];

        } catch (\Exception $e) {
            return [
                'e'   => -1,
                'msg' => 'System error'
            ];
        }

    }

    // Method to send Get request to url
    private static function doCurl($url)
    {
        $data = array();
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch), true);
            curl_close($ch);
            //            curl_error($ch)
        } catch (Exception $e) {

        }
        return $data;

    }
}