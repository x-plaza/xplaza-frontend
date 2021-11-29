<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class homeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('authAndAcl');
//    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {

        $api_url = env('API_BASE_URL')."/api/city";
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
        $decodedData = json_decode($curlOutput);
        $city_data = isset($decodedData->data) ? $decodedData->data : [];


        $category_data = ( Session::get( 'category_data_array' ) ) ? Session::get( 'category_data_array' ) : null;
        if ($category_data == null){
            $api_url = env('API_BASE_URL')."/api/category";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'GET', [] );
            $decodedData = json_decode($curlOutput);
            $category_data = isset($decodedData->data) ? $decodedData->data : [];

            session()->put( 'category_data_array', $category_data );
            Session::save();
        }
        $cubCat = [];

        return view('home',compact('city_data','category_data','cubCat'));
    }

    public function dashboardContent(Request $request)
    {
        try {

            $api_url = env('API_BASE_URL')."/api/dashboard?shop_id=".intval($request->get('shop_id'));
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
            $decodedData = json_decode($curlOutput);
            $shop_data = isset($decodedData->data) ? $decodedData->data : [];


            $public_html = strval(view("dashboard_content", compact('shop_data')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'No data found']);
        }
    }
}
