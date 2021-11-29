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
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $product_data = isset($decodedData->data) ? $decodedData->data : [];
        $public_html = strval(view("home_content.my_order", compact('product_data')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
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
