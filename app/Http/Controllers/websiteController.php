<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Storage;
use Session;

class websiteController extends Controller
{

    public function allTrendingProducts()
    {
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

        return view('all_trending_products',compact('city_data','category_data','cubCat'));

    }


    public function productByCategory($cat_id)
    {
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

        $cat_api_url = env('API_BASE_URL')."/api/category/".intval($cat_id);
        $cat_curlOutput  = HandleApi::getCURLOutput( $cat_api_url, 'GET', [] );
        $cat_decodedData = json_decode($cat_curlOutput);
        $cat_data = isset($cat_decodedData->data) ? $cat_decodedData->data : [];

        $category_name = isset($cat_data->name) ? $cat_data->name : 'x-winkel';

        $cubCat = [];

        return view('products_by_category',compact('city_data','category_data','cubCat','cat_id','category_name'));

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopSelection(Request $request)
    {
        $shop_id = $request->get('shop_id');
        $session_selected_shop_id = Session::get( 'selected_shop_id' );
        if($session_selected_shop_id != null && ($session_selected_shop_id != $shop_id)){
            session()->put( 'cart_item_array', [] );
            Session::save();
        }
        $othersDataArray = ( Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : null;
        $othersDataArray['shop_id'] = $request->get('shop_id');
        $othersDataArray['shop_name'] = $request->get('shop_name');
        $othersDataArray['location_id'] = $request->get('location_id');
        $othersDataArray['city_id'] = $request->get('city_id');

        session()->put( 'session_others_array', $othersDataArray );
        Session::save();
        session()->put( 'selected_shop_id', $shop_id );
        Session::save();
        return response()->json(['responseCode' => 1]);
    }

    public function trendingProductListForShop(Request $request)
    {
        $shop_id = intval($request->get('shop_id'));
        $api_url = env('API_BASE_URL')."/api/product/by-shop?shop_id=".$shop_id;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $public_html = strval(view("home_content.trending_product_content", compact('product_data')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function trendingProductListAllData(Request $request)
    {
        $shop_id = intval($request->get('shop_id'));
        $api_url = env('API_BASE_URL')."/api/product/by-shop?shop_id=".$shop_id;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $public_html = strval(view("home_content.trending_product_content_all_data", compact('product_data')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function ProductListBySubCat(Request $request)
    {
        $shop_id = intval($request->get('shop_id'));
        $product_cat_id = intval($request->get('product_cat_id'));

        $api_url = env('API_BASE_URL')."/api/product/by-category?category_id=".intval($product_cat_id)."&shop_id=".intval($shop_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $has_product = json_decode($curlOutput,true);

        if(count($has_product['data']) == 0){
            $public_html = '<h3><center>No product found</center></h3>';
        }else{
            $public_html = strval(view("home_content.product_content_all_data", compact('product_data')));
        }
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    /**
     * @param $term
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProductList(Request $request)
    {
        $product_name = trim($request->term);
        $shop_id = Session::get( 'selected_shop_id' );
        $api_url = env('API_BASE_URL')."/api/product/by-name?product_name=".$product_name."&shop_id=".intval($shop_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];

        $output = array();

        if(count($product_data) > 0)
        {
            foreach($product_data as $row)
            {
                $image_full_img = 'website_src/product_sample.png';
                if(isset($row->productImages[0]->name)){
                    $image_full_img = env('IMAGE_BASE_URL')."/item_image/".$row->productImages[0]->name;
                }
                $full_url = url('/website/item-details/'.$row->id);
                $temp_array = array();
                $temp_array['value'] = $row->id;
                $temp_array['label'] = '<a href="'.$full_url.'"><img src="'.$image_full_img.'" width="35" height="25" /></a>&nbsp;&nbsp;&nbsp;'.$row->name.'';
                $output[] = $temp_array;
            }
        }
        else
        {
            $output['value'] = '';
            $output['label'] = 'No Record Found';
        }

   //     dd($output);

        return response()->json($output);
    }


    public function itemDetails($item_id)
    {
        $product_id = intval($item_id);
        $shop_id = Session::get( 'selected_shop_id' );
        $api_url = env('API_BASE_URL')."/api/product/".$product_id;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $has_product_data = isset($decodedData->data) ? true : false;

      //  $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];

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

        $authId = Session::get( 'auth_user_id' );
        $imageName = isset($product_data->productImageList[0]->name) ? $product_data->productImageList[0]->name : null;
        $imagePath = 'website_src/product_sample.png';
        if (isset($imageName)){
            $imagePath = env('IMAGE_BASE_URL')."/item_image/".$imageName;
        }

        return view('product_details',compact('city_data','category_data','cubCat','authId','product_data','has_product_data','imagePath'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $request_itemimage = $request->get('itemimage');
        $request_itemname = $request->get('itemname');
        $request_itemunit = $request->get('itemunit');
        $request_itemprice = $request->get('itemprice');
        $request_addedQuantity = $request->get('addedQuantity');

        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];

        $details = [];
        $details['item_code'] = $request_itemcode;
        $details['item_image'] = $request_itemimage;
        $details['item_unit'] = $request_itemunit;
        $details['item_unit_price'] = $request_itemprice;
        $details['quantity'] = $request_addedQuantity;
        $details['currency_id'] = $request->get('itemcurrencyid');
        $details['product_id'] = $request->get('itemcode');
        $details['item_name'] = $request->get('itemname');
        $details['item_category'] = $request->get('itemcategoryname');
        $details['item_var_type_name'] = $request->get('itemvartypename');
        $details['item_var_type_value'] = $request->get('itemvartypevalue');
        $details['quantity_type'] = $request->get('itemquantitytype');
        $item_array[$request_itemcode] = $details;

      //  array_push( $session_cart_item_array, $item_array );
        if (isset($session_cart_item_array[$request_itemcode])){
            $session_cart_item_array[$request_itemcode]['quantity'] = $session_cart_item_array[$request_itemcode]['quantity'] +1;
            $finalItemArray = $session_cart_item_array;
        }else{
            $finalItemArray = $session_cart_item_array+$item_array;
        }

        session()->put( 'cart_item_array', $finalItemArray );
        Session::save();

        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }


    public function addToCartById(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $request_itemimage = $request->get('itemimage');
        $request_addedQuantity = $request->get('addedQuantity');

        $api_url = env('API_BASE_URL')."/api/product/".intval($request_itemcode);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product = isset($decodedData->data) ? $decodedData->data : [];

        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];

        $details = [];
        $details['item_code'] = $request_itemcode;
        $details['item_image'] = $request_itemimage;
        $details['item_unit'] = $product->product_var_type_value." ".$product->product_var_type_name;
        $details['item_unit_price'] = ($product->discounted_price) ? $product->discounted_price : $product->selling_price;
        $details['quantity'] = $request_addedQuantity;
        $details['currency_id'] = $product->currency_id;
        $details['product_id'] = $product->id;
        $details['item_name'] = $product->name;
        $details['item_category'] = $product->category_name;
        $details['item_var_type_name'] = $product->product_var_type_name;
        $details['item_var_type_value'] = $product->product_var_type_value;
        $details['quantity_type'] = $product->product_var_type_name;
        $item_array[$request_itemcode] = $details;

        //  array_push( $session_cart_item_array, $item_array );
        if (isset($session_cart_item_array[$request_itemcode])){
            $session_cart_item_array[$request_itemcode]['quantity'] = $session_cart_item_array[$request_itemcode]['quantity'] +1;
            $finalItemArray = $session_cart_item_array;
        }else{
            $finalItemArray = $session_cart_item_array+$item_array;
        }

        session()->put( 'cart_item_array', $finalItemArray );
        Session::save();

        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }


    public function openCartList(Request $request)
    {
        $finalItemArray = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function addQuantity(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        if (isset($session_cart_item_array[$request_itemcode])){
            $session_cart_item_array[$request_itemcode]['quantity'] = $session_cart_item_array[$request_itemcode]['quantity'] +1;
        }

        $totalPrice = 0;
        foreach ($session_cart_item_array as $item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
        }

        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $deliveryCost  = HandleApi::getDeliveryCost($totalPrice);
        $grand_total = $totalPrice+$deliveryCost;
        return response()->json(['responseCode' => 1, 'price' => $totalPrice, 'delivery_cost' => $deliveryCost, 'grand_totL' => $grand_total]);
    }

    public function addQuantityFromSitebar(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        if (isset($session_cart_item_array[$request_itemcode])){
            $session_cart_item_array[$request_itemcode]['quantity'] = $session_cart_item_array[$request_itemcode]['quantity'] +1;
        }

        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $finalItemArray = $session_cart_item_array;
        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }


    public function minusQuantityFromSitebar(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        if (isset($session_cart_item_array[$request_itemcode])){
            $value = $session_cart_item_array[$request_itemcode]['quantity'] -1;
            if ($value < 1){$value = 1;}
            $session_cart_item_array[$request_itemcode]['quantity'] = $value;
        }

        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $finalItemArray = $session_cart_item_array;
        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }


    public function removeQuantity(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        if (isset($session_cart_item_array[$request_itemcode])){
            $session_cart_item_array[$request_itemcode]['quantity'] = $session_cart_item_array[$request_itemcode]['quantity'] -1;
        }

        $totalPrice = 0;
        foreach ($session_cart_item_array as $item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
        }
        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $deliveryCost  = HandleApi::getDeliveryCost($totalPrice);
        $grand_total = $totalPrice+$deliveryCost;
        return response()->json(['responseCode' => 1, 'price' => $totalPrice, 'delivery_cost' => $deliveryCost, 'grand_totL' => $grand_total]);
    }


    public function removeItem(Request $request)
    {
        $request_itemcode = $request->get('itemcode');

        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];

        unset($session_cart_item_array[$request_itemcode]);
        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $totalPrice = 0;
        foreach ($session_cart_item_array as $item){
            $totalPrice += $item['item_unit_price']*$item['quantity'];
        }

        $deliveryCost  = HandleApi::getDeliveryCost($totalPrice);
        $grand_total = $totalPrice+$deliveryCost;
        return response()->json(['responseCode' => 1, 'price' => $totalPrice, 'delivery_cost' => $deliveryCost, 'grand_totL' => $grand_total]);
    }


    public function itemCounter(Request $request)
    {
        $itemCounter = count(Session::get( 'cart_item_array' ));
        return response()->json(['responseCode' => 1, 'data' => $itemCounter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFromCart(Request $request)
    {
        $request_itemcode = $request->get('itemcode');
        $session_cart_item_array = ( Session::get( 'cart_item_array' ) ) ? Session::get( 'cart_item_array' ) : [];
        unset($session_cart_item_array[$request_itemcode]);
        session()->put( 'cart_item_array', $session_cart_item_array );
        Session::save();

        $finalItemArray = $session_cart_item_array;
        $public_html = strval(view("home_content.cart_item", compact('finalItemArray')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function locationData(Request $request)
    {
        $city_id = $request->get('city_id');
        $api_url = env('API_BASE_URL')."/api/location";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput,true);
        $location_data = isset($decodedData['data']) ? $decodedData['data'] : [];

        $filteredArray = [];
        foreach ($location_data as $data){
            $subData = [];
            if ($data['city_id'] == $city_id){
                $subData['id'] = $data['id'];
                $subData['name'] = $data['name'];
            }else{
                continue;
            }
            $filteredArray[] = $subData;
        }

        return response()->json(['responseCode' => 1, 'locations' => $filteredArray]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopData(Request $request)
    {
        $location_id = $request->get('location_id');
        $api_url = env('API_BASE_URL')."/api/shop/by-location/".intval($location_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput,true);
        $shop_data = isset($decodedData['data']) ? $decodedData['data'] : [];

        $filteredArray = [];
        foreach ($shop_data as $data){
            $subData = [];
            if ($data['location_id'] == $location_id){
                $subData['id'] = $data['id'];
                $subData['name'] = $data['name'];
            }else{
                continue;
            }
            $filteredArray[] = $subData;
        }

        if(count($filteredArray) == 0){
            return response()->json(['responseCode' => 2, 'shops' => $filteredArray]);
        }

        return response()->json(['responseCode' => 1, 'shops' => $filteredArray]);
    }


    public function getOtp(Request $request)
    {
        $email = $request->get('reg_email');
        if (!isset($email) || $email == null){
            return response()->json(['responseCode' => 2, 'message' => 'Not sent otp']);
        }

        $api_url = env('API_BASE_URL')."/api/confirmation-token/to-customer?username=".$email;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
        $response = json_decode($curlOutput,true);

        if (!isset($response['status']) || $response['status'] != 201){
            return response()->json(['responseCode' => 0, 'message' => 'Not sent otp']);
        }

        return response()->json(['responseCode' => 1, 'message' => 'Successfully sent otp']);
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
