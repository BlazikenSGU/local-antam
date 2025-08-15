<?php
/**
 * User: LuanNT
 * Date: 20/12/2018
 * Time: 9:32 SA
 */

namespace App\Utils;

class GoogleMaps
{
    public static function getLatLong($address = '', $return_sql_point = true)
    {
        if (empty($address))
            return null;

        try {

            $endpoint = "https://maps.google.com/maps/api/geocode/json";
            $client = new \GuzzleHttp\Client();

            $response = $client->request('GET', $endpoint, [
                'query' => [
                    'address' => $address,
                    'key'     => config('constants.google_maps_key')
                ]
            ]);

//        $statusCode = $response->getStatusCode();

            $content = json_decode($response->getBody(), true);
            if ($content['status'] == 'OK' && isset($content['results'][0]['geometry']['location'])) {
                $lat = $content['results'][0]['geometry']['location']['lat'];
                $lng = $content['results'][0]['geometry']['location']['lng'];

                if ($return_sql_point) {
                    $location = "POINT({$lng},{$lat})";
                    return \DB::raw($location);
                } else {
                    return [
                        'lat'  => $lat,
                        'long' => $lng,
                    ];
                }
            }
        } catch (Exception $e) {

        }
        return null;
    }
}