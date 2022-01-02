<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Storage;
use Session;

class checkoutController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {

        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        if(count($session_cart_item_array) == 0){return redirect('/');}

        $api_url = env('API_BASE_URL')."/api/city";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $city_data = isset($decodedData->data) ? $decodedData->data : [];

        $category_data = (Session::get( 'category_data_array' ) ) ? Session::get( 'category_data_array' ) : null;
        if ($category_data == null){
            $api_url = env('API_BASE_URL')."/api/category";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put( 'category_data_array', $category_data );
            Session::save();
        }

        $cubCat = [];

        $totalPrice = 0;
        $sessionData = Session::get( 'cart_item_array' );
        $finalItemArray = isset($sessionData) ? $sessionData : [];
        foreach($finalItemArray as $item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
        }
        $deliveryCost      = HandleApi::getDeliveryCost($totalPrice);
      //  $deliverySchedule  = HandleApi::getDeliverySchedule($totalPrice);

        $authId = Session::get( 'auth_user_id' );

        return view('checkout',compact('city_data','category_data','cubCat','deliveryCost','authId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {

        //Checking Auth
        //====================
        $auth_user_id = ( Session::get( 'auth_user_id' ) ) ? Session::get( 'auth_user_id' ) : null;
        if (!isset($auth_user_id) || $auth_user_id == null){
            return response()->json(['responseCode' => 5, 'message' => 'Please sign up first']);
        }

        $delivery_schedule_id  = $request->get('delivery_schedule_id');
        $delivery_schedule_text  = $request->get('delivery_schedule_text');
     //   $customer_full_name  = $request->get('customer_full_name');
        $customer_address  = $request->get('customer_address');
        $coupon_number  = $request->get('coupon_number');
        $customer_mobile  = $request->get('customer_mobile');
        $delivery_date  = $request->get('delivery_date');

        if (!isset($delivery_schedule_text)  || !isset($customer_address) || !isset($customer_mobile) ){
            return response()->json(['responseCode' => 0, 'message' => 'Input data missing']);
        }

        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        $session_others_array = ( Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : [];
        if(count($session_cart_item_array) == 0){return response()->json(['responseCode' => 0, 'message' => 'Cart is empty']);}


        $itemData = [];
        $subItemData = [];
        $totalPrice = 0;
        foreach ($session_cart_item_array as $key=>$item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
            $subItemData['currency_id'] = intval($item['currency_id']);
            $subItemData['product_id'] = intval($item['product_id']);
            $subItemData['item_name'] = $item['item_name'];
            $subItemData['item_image'] = $item['item_image'];
            $subItemData['item_category'] = $item['item_category'];
            $subItemData['item_var_type_name'] = $item['item_var_type_name'];
            $subItemData['item_var_type_value'] = intval($item['item_var_type_value']);
            $subItemData['quantity'] = intval($item['quantity']);
            $subItemData['quantity_type'] = $item['quantity_type'];
            $itemData[] = $subItemData;
        }

        $session_others_array = (Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : null;

        $customer_email = $session_others_array['user_email'];
        $customer_full_name = $session_others_array['user_name'];

        $finalOrderData = [
           "shop_id"=>intval($session_others_array['shop_id']),
           "shop_name"=>$session_others_array['shop_name'],
           "customer_id"=>intval($auth_user_id),
           "customer_name"=>trim($customer_full_name),
           "mobile_no"=>trim($customer_mobile),
           "delivery_address"=>trim($customer_address),
           "additional_info"=>"No",
           "delivery_schedule_start"=>explode('-',$delivery_schedule_id)[0],
           "delivery_schedule_end"=>explode('-',$delivery_schedule_id)[1],
           "received_time"=>date('Y-m-d',strtotime($delivery_date))."T15:14:26.637Z",
           "date_to_deliver"=>date('Y-m-d',strtotime($delivery_date))."T15:14:26.637Z",
           "status_id"=>1,
           "currency_id"=>1,
           "coupon_id"=>null,
           "coupon_code"=>$coupon_number,
           "delivery_cost_id"=>intval($session_others_array['delivery_cost_id']),
           "orderItemList"=>$itemData
        ];

//        $finalOrderData = [
//            "shop_id"=>intval($session_others_array['shop_id']),
//            "shop_name"=>"string",
//            "customer_id"=>1,
//            "customer_name"=>"string",
//            "mobile_no"=>"string",
//            "delivery_address"=>"string",
//            "additional_info"=>"string",
//            "delivery_schedule_start"=>"09:00",
//            "delivery_schedule_end"=>"11:00",
//            "received_time"=>"2021-10-10T15:14:26.637Z",
//            "date_to_deliver"=>"2021-10-10T15:14:26.637Z",
//            "status_id"=>1,
//            "currency_id"=>1,
//            "coupon_id"=>null,
//            "coupon_code"=>null,
//            "delivery_cost_id"=>1,
//            "orderItemList"=>$itemData
//        ];


        $api_url = env('API_BASE_URL')."/api/order/add";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', json_encode($finalOrderData) );
        $response = json_decode($curlOutput,true);

        $orderResp = json_decode($response['data']);
        $grandTotalPrice = isset($orderResp->grand_total_price) ? $orderResp->grand_total_price : '';
        $invoice = isset($orderResp->invoice_number) ? $orderResp->invoice_number : '';
        if (!isset($response['status']) || $response['status'] != 201){
            return response()->json(['responseCode' => 0, 'message' => $response['message'],'Total_price'=> 'R '.$grandTotalPrice,'invoice'=>$invoice]);
        }

        session()->put( 'cart_item_array', [] );
        Session::save();
//        session()->put( 'session_others_array', null );
//        Session::save();
//        session()->put( 'selected_shop_id', null );
//        Session::save();

        return response()->json(['responseCode' => 1, 'message' => 'Order placed successfully','Total_price'=> 'R '.$grandTotalPrice,'invoice'=>$invoice]);
    }


    public function validateCoupon(Request $request)
    {
        $coupon_number = $request->get('coupon_number');
        $delivery_cost_section_hidden = floatval($request->get('delivery_cost_section_hidden'));
        if (!isset($coupon_number) || $coupon_number == null){
            return response()->json(['responseCode' => 2, 'message' => 'Coupon number required']);
        }

        $totalPrice = 0;
        $sessionData = Session::get( 'cart_item_array' );
        $finalItemArray = isset($sessionData) ? $sessionData : [];
        foreach($finalItemArray as $item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
        }

        $session_others_array = (Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : [];
        $shopId = intval($session_others_array['shop_id']);

        $api_url = env('API_BASE_URL')."/api/coupon/validate-coupon?coupon_code=".$coupon_number."&net_order_amount=".$totalPrice."&shop_id=".$shopId;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
        $response = json_decode($curlOutput,true);

        if (!isset($response['status']) || $response['status'] != 200){
            return response()->json(['responseCode' => 0, 'message' => $response['message'],'final_amount' => round($totalPrice+$delivery_cost_section_hidden,2)]);
        }

        $finalAmount = floatval($totalPrice) - floatval($response['data']);
        $message = floatval($response['data']);
        return response()->json(['responseCode' => 1, 'final_amount' => round($finalAmount+$delivery_cost_section_hidden,2), 'message' => $message]);
    }


    /**
     * @param Request $request
     */
    public function deliveryTimeSlot(Request $request)
    {
        date_default_timezone_set("Africa/Johannesburg");
        $deliveryDate = $request->get('delivery_date');
        $selectedDay = date('D', strtotime($deliveryDate));
        $deliverySchedule  = HandleApi::getDeliverySchedule();

        $filteredArray = [];
        foreach ($deliverySchedule as $data){
            $subData = [];
            if (strtoupper($data['day_name']) == strtoupper($selectedDay)){
                if (date('D') == $selectedDay){
                    if (intval(date('H')) < intval(substr(explode('-',$data['day_slot'])[1],0,2))){
                        $subData['id'] = $data['schedule_id'];
                        $subData['day_slot'] = $data['day_slot'];
                    }else{
                        continue;
                    }
                }else{
                    $subData['id'] = $data['schedule_id'];
                    $subData['day_slot'] = $data['day_slot'];
                }
            }else{
                continue;
            }
            $filteredArray[] = $subData;
        }

        if (count($filteredArray) == 0){
            return response()->json(['responseCode' => 2, 'schedule_data' => $filteredArray]);
        }

        return response()->json(['responseCode' => 1, 'schedule_data' => $filteredArray]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
