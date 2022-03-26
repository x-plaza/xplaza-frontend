<?php

namespace App\Libraries;

use Storage;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HandleApi {

	public function __construct() {

	}

	/**
	 * @param $curloptURL
	 * @param $method
	 * @param $fieldData
	 *
	 * @return bool|string
	 */

	public static function getCURLOutput( $curloptURL, $method, $fieldData ) {

		$onlyToken = 'Dummy';//self::getValidToken();

		if ( isset( $onlyToken ) ) {

			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_URL            => "$curloptURL",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "$method",
				CURLOPT_POSTFIELDS     => $fieldData,
				CURLOPT_HTTPHEADER     => array(
					"Authorization: Bearer $onlyToken",
					"Content-Type: application/json",
				),
			) );
			$response = curl_exec( $curl );
			curl_close( $curl );

			return $response;
		} else {
			return false;
		}
	}


	public static function getDeliveryCost($totalPrice){

        $sessionDeliveryCostSlots = Session::get( 'session_delivery_cost_slots' );
        if ($sessionDeliveryCostSlots == null){
            $api_url = env('API_BASE_URL')."/api/delivery-cost";
            $curlOutput  = self::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput,true);
            $sessionDeliveryCostSlots = isset($decodedData['data']) ? $decodedData['data'] : null;

            session()->put( 'session_delivery_cost_slots', $sessionDeliveryCostSlots );
            Session::save();
        }

        $deliveryCost = 0;
        $deliveryCostId = 0;
        foreach ($sessionDeliveryCostSlots as $slots){
            $explodedData = explode('-',$slots['delivery_slab_range']);
            if(isset($explodedData[0]) && isset($explodedData[1]) ){
                if ($totalPrice >= intval($explodedData[0]) && $totalPrice <= intval($explodedData[1])){
                    $deliveryCost = intval($slots['cost']);
                    $deliveryCostId = $slots['id'];
                    break;
                }
            }
        }

        $othersDataArray = Session::get( 'session_others_array' );
        $othersDataArray['delivery_cost'] = $deliveryCost;
        $othersDataArray['delivery_cost_id'] = $deliveryCostId;
        session()->put( 'session_others_array', $othersDataArray );
        Session::save();

        return $deliveryCost;
    }


    public static function getDeliverySchedule(){

        $sessionDeliverySchedule = Session::get( 'session_delivery_schedule' );
        if ($sessionDeliverySchedule == null){
            $api_url = env('API_BASE_URL')."/api/delivery-schedules";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput,true);
            $delivery_schedule_data = isset($decodedData['data']) ? $decodedData['data'] : [];

            $scheduleFinalArray = [];
            foreach ($delivery_schedule_data as $data){
                $subString = [];
                foreach ($data['delivery_schedules'] as $schedule){
                    $subString['day_name'] = $data['day_name'];
                    $subString['schedule_id'] = $schedule['delivery_schedule_id'];
                    $subString['day_slot'] = $schedule['delivery_schedule_start'].'-'.$schedule['delivery_schedule_end'];
                    $scheduleFinalArray[]= $subString;
                }
            }
            $sessionDeliverySchedule = $scheduleFinalArray;
            session()->put( 'session_delivery_schedule', $scheduleFinalArray );
            Session::save();
        }

        return $sessionDeliverySchedule;

    }

    public static function getParentCatName($catId){

        $category_data = ( Session::get( 'category_data_array' ) ) ? Session::get( 'category_data_array' ) : null;

        if ($category_data == null){
            $api_url = env('API_BASE_URL')."/api/category";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put( 'category_data_array', $category_data );
            Session::save();
        }

        $parentCatName = '';
        foreach ($category_data as $data){
            if ($data->id == $catId){
                $parentCatName = $data->name;
                break;
            }
        }

        return $parentCatName;
    }


    public static function searchProductData()
    {
        $shop_id = Session::get( 'selected_shop_id' );
        $api_url = env('API_BASE_URL')."/api/product/by-shop?shop_id=".intval($shop_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $allData = [];
//        $subData['id'] = -99999;
//        $subData['name'] = 'Search product';
//        $allData[] =  $subData;
        foreach ($product_data as $data){
          //  if ($data->quantity < 1){continue;}
            $image_name = isset($data->productImageList[0]->name) ? $data->productImageList[0]->name : '#';
            $subData['quantity'] = $data->quantity;
            $subData['id'] = $data->id;
            $subData['name'] = $data->name .' ( '.$data->product_var_type_value.' '.$data->product_var_type_name.' )';
            $subData['img_url'] = env('IMAGE_BASE_URL','https://test-admin.xwinkel.com').'/item_image/'.$image_name;
            $allData[] =  $subData;
        }
        return $allData;

    }
    public static function getSubCat($catId){

        $category_data = ( Session::get( 'category_data_array' ) ) ? Session::get( 'category_data_array' ) : null;

        if ($category_data == null){
            $api_url = env('API_BASE_URL')."/api/category";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put( 'category_data_array', $category_data );
            Session::save();
        }

        $subCatArray = [];
        $subData = [];
        foreach ($category_data as $data){
            if ($data->parent_category_id == $catId){
                $subData['id'] =  $data->id;
                $subData['name'] =  $data->name;
            }else{
                continue;
            }
            $subCatArray[] =$subData;
        }

        return $subCatArray;
    }
	/*     * ****************************End of Class***************************** */
}
