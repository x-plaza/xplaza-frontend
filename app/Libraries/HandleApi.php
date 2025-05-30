<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HandleApi
{
    public function __construct() {}

    public static function getValidToken()
    {
        $client = new Client(['verify' => false]);
        $curloptURL = config('services.api.base_url').'/authenticate';
        $fieldData = ['username' => env('ADMIN_USERNAME'), 'password' => env('ADMIN_PASSWORD')];
        try {
            $response = $client->post($curloptURL, [
                'json' => $fieldData,
                'headers' => [
                    'Authorization' => 'Bearer',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10,
            ]);
            $decodedToken = json_decode($response->getBody(), true);
            return isset($decodedToken['jwtToken']) ? $decodedToken['jwtToken'] : null;
        } catch (RequestException $e) {
            return null;
        }
    }

    /**
     * @return bool|string
     */
    public static function getCURLOutput($curloptURL, $method, $fieldData)
    {
        $onlyToken = self::getValidToken();
        if (isset($onlyToken)) {
            $client = new Client(['verify' => false]);
            $options = [
                'headers' => [
                    'Authorization' => "Bearer $onlyToken",
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10,
            ];
            if ($method === 'POST' || $method === 'PUT') {
                $options['body'] = $fieldData;
            }
            try {
                $response = $client->request($method, $curloptURL, $options);
                return $response->getBody()->getContents();
            } catch (RequestException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getDeliveryCost($totalPrice)
    {
        $sessionDeliveryCostSlots = Session::get('session_delivery_cost_slots');
        if ($sessionDeliveryCostSlots == null) {
            $api_url = config('services.api.base_url').'/delivery-costs';
            $curlOutput = self::getCURLOutput($api_url, 'GET', []);
            $decodedData = json_decode($curlOutput, true);
            $sessionDeliveryCostSlots = isset($decodedData['data']) ? $decodedData['data'] : null;

            session()->put('session_delivery_cost_slots', $sessionDeliveryCostSlots);
            Session::save();
        }

        $deliveryCost = 0;
        $deliveryCostId = 0;
        foreach ($sessionDeliveryCostSlots as $slots) {
            $explodedData = explode('-', $slots['delivery_slab_range']);
            if (isset($explodedData[0]) && isset($explodedData[1])) {
                if ($totalPrice >= intval($explodedData[0]) && $totalPrice <= intval($explodedData[1])) {
                    $deliveryCost = intval($slots['cost']);
                    $deliveryCostId = $slots['id'];
                    break;
                }
            }
        }

        $othersDataArray = Session::get('session_others_array');
        $othersDataArray['delivery_cost'] = $deliveryCost;
        $othersDataArray['delivery_cost_id'] = $deliveryCostId;
        session()->put('session_others_array', $othersDataArray);
        Session::save();

        return $deliveryCost;
    }

    public static function getDeliverySchedule()
    {
        $sessionDeliverySchedule = Session::get('session_delivery_schedule');
        if ($sessionDeliverySchedule == null) {
            $api_url = config('services.api.base_url').'/delivery-schedules';
            $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
            $decodedData = json_decode($curlOutput, true);
            $delivery_schedule_data = isset($decodedData['data']) ? $decodedData['data'] : [];

            $scheduleFinalArray = [];
            foreach ($delivery_schedule_data as $data) {
                $subString = [];
                foreach ($data['delivery_schedules'] as $schedule) {
                    $subString['day_name'] = $data['day_name'];
                    $subString['schedule_id'] = $schedule['delivery_schedule_id'];
                    $subString['day_slot'] = $schedule['delivery_schedule_start'].'-'.$schedule['delivery_schedule_end'];
                    $scheduleFinalArray[] = $subString;
                }
            }
            $sessionDeliverySchedule = $scheduleFinalArray;
            session()->put('session_delivery_schedule', $scheduleFinalArray);
            Session::save();
        }

        return $sessionDeliverySchedule;

    }

    public static function getParentCatName($catId)
    {
        $category_data = (Session::get('category_data_array')) ? Session::get('category_data_array') : null;

        if ($category_data == null) {
            $api_url = config('services.api.base_url').'/categories';
            $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put('category_data_array', $category_data);
            Session::save();
        }

        $parentCatName = '';
        foreach ($category_data as $data) {
            if ($data->id == $catId) {
                $parentCatName = $data->name;
                break;
            }
        }

        return $parentCatName;
    }

    public static function searchProductData()
    {
        $shop_id = Session::get('selected_shop_id');
        $api_url = config('services.api.base_url').'/products/by-shop?shop_id='.intval($shop_id);
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $allData = [];

        foreach ($product_data as $data) {
            $image_name = isset($data->productImageList[0]->name) ? $data->productImageList[0]->name : '#';
            $subData['quantity'] = $data->quantity;
            $subData['id'] = $data->id;
            $subData['name'] = $data->name.' ( '.$data->product_var_type_value.' '.$data->product_var_type_name.' )';
            $subData['img_url'] = '';
            $allData[] = $subData;
        }

        return $allData;
    }

    public static function getSubCat($catId)
    {
        $category_data = (Session::get('category_data_array')) ? Session::get('category_data_array') : null;

        if ($category_data == null) {
            $api_url = config('services.api.base_url').'/categories';
            $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put('category_data_array', $category_data);
            Session::save();
        }

        $subCatArray = [];
        $subData = [];
        foreach ($category_data as $data) {
            if ($data->parent_category_id == $catId) {
                $subData['id'] = $data->id;
                $subData['name'] = $data->name;
            } else {
                continue;
            }
            $subCatArray[] = $subData;
        }

        return $subCatArray;
    }
}
