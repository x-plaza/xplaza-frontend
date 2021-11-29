<?php

namespace App\Http\Controllers;

use App\Libraries\AclHandler;
use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class forgotPassController extends Controller
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

    public function getOtp(Request $request)
    {

        $rules = [
            'user_name'        => 'required'
        ];
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return response()->json( ['responseCode'=>0,'message'=>'Please fill up required field']);
        }

        $user_name = $request->get('user_name');

        $api_url = env('API_BASE_URL')."/api/login/send-otp?username=".$user_name;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );

        $decodedResp = json_decode($curlOutput);

        if($decodedResp->status == 201){
            return response()->json( ['responseCode'=>1,'message'=>$decodedResp->message]);
        }else{
            return response()->json( ['responseCode'=>0,'message'=>$decodedResp->message]);
        }
    }


    public function setNewPass(Request $request)
    {

        $rules = [
            'user_name'        => 'required',
            'otp_code'        => 'required',
            'new_password'        => 'required',
            'confirm_password'        => 'required'
        ];
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return response()->json( ['responseCode'=>0,'message'=>'Please fill up required field']);
        }

        $user_name = $request->get('user_name');
        $otp_code = $request->get('otp_code');
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('confirm_password');

        if ( $new_password != $confirm_password ) {
            return response()->json( ['responseCode'=>0,'message'=>'Confirm password does not match']);
        }

        $api_url = env('API_BASE_URL')."/api/login/validate-otp?OTP=".$otp_code."&username=".$user_name;
        $curlOutput  = HandleApi::getCURLOutput( $api_url, 'POST', [] );
        $decodedResp = json_decode($curlOutput);

        if($decodedResp->status != 200){
            return response()->json( ['responseCode'=>0,'message'=>$decodedResp->message]);
        }

        $change_pass_api_url = env('API_BASE_URL')."/api/login/change-password?newPassword=".$new_password."&username=".$user_name;
        $change_pass_curlOutput  = HandleApi::getCURLOutput( $change_pass_api_url, 'POST', [] );

        $change_pass_decodedResp = json_decode($change_pass_curlOutput);

        if($change_pass_decodedResp->status == 200){
            return response()->json( ['responseCode'=>1,'message'=>$change_pass_decodedResp->message]);
        }else{
            return response()->json( ['responseCode'=>0,'message'=>$change_pass_decodedResp->message]);
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
