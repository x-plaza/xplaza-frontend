<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class apiAuthenticationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function loginAttempt(Request $request)
    {

        session()->put( 'auth_user_id', 1 );
        Session::save();
        return response()->json(['responseCode' => 1, 'message' => 'login successfully']);

        $bodyData = [
            "username "=>$request->get('email'),
            "password "=>$request->get('password')
        ];
        $fieldData = json_encode($bodyData);
        $pass = $request->get('password');
        $username = $request->get('email');

//        try{
//
//            $api_url = env('API_BASE_URL')."/api/login?password=".$pass."&username=".$username;
//            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
//            $decodedResp = json_decode($curlOutput);
//
//            if(!isset($decodedResp->data->authentication)) {return redirect()->back()->with('error', "Opps! Something wrong");}
//
//            if($decodedResp->data->authentication == true){
//                $shopList = isset($decodedResp->data->shopList)? $decodedResp->data->shopList : [];
//                $userId = isset($decodedResp->data->id)? $decodedResp->data->id : '';
//                Session::put('authenticated', 'true');
//                Session::put('authData', $decodedResp->data->authData);
//                Session::put('permissions', $decodedResp->data->permissions);
//                Session::put('shopList', $shopList);
//                Session::put('userId', $userId);
//                return redirect('/home');
//            }else{
//                return redirect()->back()->with('error', "Sorry! Invalid Credentials");
//            }
//
//        } catch (\Exception $e) {
//            Session::forget('authenticated');
//            Session::forget('authData');
//            Session::forget('permissions');
//            Session::forget('shopList');
//            Session::forget('userId');
//            return redirect()->back()->with('error', "Something wrong");
//        }
    }


    public function registration(Request $request)
    {

        $reg_city = $request->get('reg_city');
        $reg_area = $request->get('reg_area');
        $reg_country = $request->get('reg_country');
        $reg_street_name = $request->get('reg_street_name');
        $reg_dob = $request->get('reg_dob');
        $reg_f_name = $request->get('reg_f_name');
        $reg_l_name = $request->get('reg_l_name');
        $reg_house_no = $request->get('reg_house_no');
        $reg_mobile = $request->get('reg_mobile');
        $reg_post_code = $request->get('reg_post_code');
        $reg_email = $request->get('reg_email');
        $reg_password = $request->get('reg_password');
        $reg_conf_password = $request->get('reg_conf_password');
        $reg_otp = $request->get('reg_otp');

        if ($reg_password != $reg_conf_password){
            return response()->json(['responseCode' => 0, 'message' => 'Password not matched']);
        }

        try{

            $bodyData = [
                "area"=>$reg_area,
                "city"=>$reg_city,
                "country"=>$reg_country,
                "date_of_birth"=>$reg_dob,
                "email"=>$reg_email,
                "first_name"=>$reg_f_name,
                "house_no"=>$reg_house_no,
                "last_name"=>$reg_l_name,
                "mobile_no"=>$reg_mobile,
                "otp"=>$reg_otp,
                "password"=>$reg_password,
                "postcode"=>$reg_post_code,
                "salt"=>"test",
                "street_name"=>$reg_street_name
            ];

            $api_url = env('API_BASE_URL')."/api/customer-signup";
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', json_encode($bodyData) );
            $response = json_decode($curlOutput,true);

            if (!isset($response['status']) || $response['status'] != 201){
                return response()->json(['responseCode' => 0, 'message' => $response['message']]);
            }

            $api_url = env('API_BASE_URL')."/api/customer-login?password=".$reg_password."&username=".$reg_email;
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
            $decodedData = json_decode($curlOutput,true);
            $shop_data = isset($decodedData['data']) ? $decodedData['data'] : [];

            session()->put( 'auth_user_id', $shop_data['id'] );
            Session::save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully registered']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' =>'Registration not completed']);
        }
    }

    public function initLogin(Request $request)
    {

        $login_email = $request->get('login_email');
        $login_password = $request->get('login_password');

        try{

            $api_url = env('API_BASE_URL')."/api/customer-login?password=".$login_password."&username=".$login_email;
            $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
            $decodedData = json_decode($curlOutput,true);
            $auth_data = isset($decodedData['data']) ? $decodedData['data'] : [];

            if (!isset($auth_data['authentication']) || $auth_data['authentication'] != true){
                return response()->json(['responseCode' => 0, 'message' => $decodedData['message']]);
            }

            $session_others_array = ( Session::get( 'session_others_array' ) ) ? Session::get( 'session_others_array' ) : null;
            $session_others_array['user_email'] = $auth_data['email'];
            $session_others_array['user_name'] = $auth_data['name'];

            session()->put( 'auth_user_id', $auth_data['id'] );
            Session::save();
            session()->put( 'session_others_array', $session_others_array );
            Session::save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully logged in']);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' =>'Invalid credential']);
        }
    }


    public function logOutAttempt()
    {
        session()->put( 'cart_item_array', [] );
        Session::save();
        session()->put( 'session_others_array', null );
        Session::save();
//        session()->put( 'selected_shop_id', null );
//        Session::save();
        session()->put( 'auth_user_id', null );
        Session::save();

        return redirect('/');
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
