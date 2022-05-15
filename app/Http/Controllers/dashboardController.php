<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Storage;
use Session;

class dashboardController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $api_url = env('API_BASE_URL')."/api/city";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $city_data = isset($decodedData->data) ? $decodedData->data : [];

        $api_url = env('API_BASE_URL')."/api/location";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $location_data = isset($decodedData->data) ? $decodedData->data : [];

        $api_url = env('API_BASE_URL')."/api/shop?user_id=8";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $shop_data = isset($decodedData->data) ? $decodedData->data : [];

        $api_url = env('API_BASE_URL')."/api/category";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $category_data = isset($decodedData->data) ? $decodedData->data : [];

        $cubCat = [];
        $val = [];
        foreach ($category_data as $category){
            if ($category->parent_category_id == 0 ){continue;}
            $val['category_id'] = $category->id;
            $val['parent'] = $category->parent_category_id;
            $val['name'] = $category->name;
            $cubCat[] = $val;
        }

        return view('my_dashboard',compact('city_data','location_data','shop_data','category_data','cubCat'));

    }


    public function myOrderList(Request $request)
    {
        $auth_user_id = ( Session::get( 'auth_user_id' ) ) ? Session::get( 'auth_user_id' ) : null;

        $api_url = env('API_BASE_URL')."/api/order/by-customer?customer_id=".intval($auth_user_id);
      //  $api_url = env('API_BASE_URL')."/api/order/by-customer/".intval($auth_user_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $public_html = strval(view("home_content.my_order", compact('product_data')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function myProfile(Request $request)
    {
        $auth_user_id = ( Session::get( 'auth_user_id' ) ) ? Session::get( 'auth_user_id' ) : null;
        $session_others_array = ( Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : null;

       // $api_url = env('API_BASE_URL')."/api/customer/".urlencode($session_others_array['user_email']);
        $api_url = env('API_BASE_URL')."/api/customer/".intval($auth_user_id);
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $profile_data = isset($decodedData->data) ? $decodedData->data : [];
        $email = $session_others_array['user_email'];

        $public_html = strval(view("home_content.my_profile", compact('profile_data','email')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $auth_user_id = ( Session::get( 'auth_user_id' ) ) ? Session::get( 'auth_user_id' ) : null;
        $session_others_array = ( Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : null;

        $bodyData = [
            "id"=>$auth_user_id,
            "area"=>$request->get('area'),
            "city"=>$request->get('city'),
            "country"=>$request->get('country'),
            "date_of_birth"=>'1990-04-18T20:33:36.052Z',
            "email"=>$session_others_array['user_email'],
            "first_name"=>$request->get('first_name'),
            "house_no"=>$request->get('house_no'),
            "last_name"=>$request->get('last_name'),
            "mobile_no"=>$request->get('mobile_no'),
            "postcode"=>$request->get('postcode'),
            "street_name"=>$request->get('street_name')
        ];
        $fieldData = json_encode($bodyData);

        $api_url = env('API_BASE_URL')."/api/customer/update";
        $curlOutputMain  = HandleApi::getCURLOutput( $api_url, 'PUT', $fieldData );

        $decodedResp = json_decode($curlOutputMain);

        if($decodedResp->status == 200){
            return response()->json( ['responseCode'=>1,'message'=>'Successfully updated']);
        }else{
            return response()->json( ['responseCode'=>0,'message'=>$decodedResp->message]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
