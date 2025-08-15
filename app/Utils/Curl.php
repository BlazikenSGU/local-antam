<?php

namespace App\Utils;

class Curl
{
    public static function get($arrInit, $arrParams)
    {
        $arrResult = array(
            'http_status' => 0,//200:success
            'curl_error'  => '',
            'data_return' => NULL
        );

        $strQuery = '';
        if (!empty($arrParams)) {
            $arrData = array();
            foreach ($arrParams as $key => $value) {
                $arrData[$key] = $key . '=' . $value;
            }
            $strQuery = implode('&', $arrData);
        }

        $arrInitDefault = array(
            'URL'                    => NULL,
            'CURLOPT_SSL_VERIFYPEER' => FALSE,
            'CURLOPT_SSL_VERIFYHOST' => FALSE,
            'CURLOPT_RETURNTRANSFER' => 1,
            'CURLOPT_CONNECTTIMEOUT' => 10,
            'CURLOPT_TIMEOUT'        => 10,
            'RETURN_TYPE'            => 'json'
        );

        $arrInit = array_merge($arrInitDefault, $arrInit);
        if (empty($arrInit['URL'])) {
            $arrResult['curl_error'] = 'Url API NULL';
            return $arrResult;
        } else {
            $arrInit['URL'] = $arrInit['URL'] . '?' . $strQuery;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $arrInit['URL']);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $arrInit['CURLOPT_SSL_VERIFYPEER']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $arrInit['CURLOPT_SSL_VERIFYHOST']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $arrInit['CURLOPT_RETURNTRANSFER']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $arrInit['CURLOPT_CONNECTTIMEOUT']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $arrInit['CURLOPT_TIMEOUT']);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $arrResult['http_status'] = $status;
        if ($error) {
            $arrResult['curl_error'] = $error;
        }
        $arrResult['data_return'] = $result;
        if (strtolower($arrInit['RETURN_TYPE']) == 'json') {
            if ($result) {
                $arrResult['data_return'] = @json_decode($result, TRUE);
            }
        }

        return $arrResult;
    }

    public static function post($arrInit, $arrParams)
    {
        $arrResult = array(
            'http_status' => 0,//200:success
            'curl_error'  => '',
            'data_return' => NULL
        );

        $strQuery = '';
        if (!empty($arrParams)) {
            $arrData = array();
            foreach ($arrParams as $key => $value) {
                $arrData[$key] = $key . '=' . $value;
            }
            $strQuery = implode('&', $arrData);
        }

        $arrInitDefault = array(
            'URL'                    => NULL,
            'CURLOPT_SSL_VERIFYPEER' => FALSE,
            'CURLOPT_SSL_VERIFYHOST' => FALSE,
            'CURLOPT_RETURNTRANSFER' => 1,
            'CURLOPT_CONNECTTIMEOUT' => 10,
            'CURLOPT_TIMEOUT'        => 10,
            'RETURN_TYPE'            => 'json'
        );

        $arrInit = array_merge($arrInitDefault, $arrInit);
        //echo $arrInit['URL'] . '?' . $strQuery;
        if (empty($arrInit['URL'])) {
            $arrResult['curl_error'] = 'Url API Empty';
            return $arrResult;
        }
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $arrInit['URL']);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $arrInit['CURLOPT_SSL_VERIFYPEER']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $arrInit['CURLOPT_SSL_VERIFYHOST']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $arrInit['CURLOPT_RETURNTRANSFER']);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $arrInit['CURLOPT_CONNECTTIMEOUT']);
            curl_setopt($ch, CURLOPT_TIMEOUT, $arrInit['CURLOPT_TIMEOUT']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $strQuery);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        } catch (Exception $e) {
            return $arrResult = array(
                'http_status' => -1,
                'curl_error'  => 'Can not connect',
                'data_return' => NULL
            );
        }
        $arrResult['http_status'] = $status;
        if ($error) {
            $arrResult['curl_error'] = $error;
        }
        $arrResult['data_return'] = $result;
        if (strtolower($arrInit['RETURN_TYPE']) == 'json') {
            if ($result) {

                $arrResult['data_return'] = @json_decode($result, TRUE);
            }
        }
        return $arrResult;
    }
}