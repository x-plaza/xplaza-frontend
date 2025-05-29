<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class usersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'confirm_password' => 'required',
            'new_password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $old_password = $request->get('old_password');
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('confirm_password');
        $email = Session::get('authData')->user_name;

        if ($confirm_password != $new_password) {
            return response()->json(['responseCode' => 0, 'message' => 'Confirm password does not match']);
        }

        $bodyData = [
            'newPassword' => $new_password,
            'oldPassword' => $old_password,
            'username' => $email,
        ];

        $finalData = json_encode($bodyData);

        $api_url = env('API_BASE_URL').'/admin-users/change-password?newPassword='.$new_password.'&oldPassword='.$old_password.'&username='.$email;
        $curlOutput = HandleApi::getCURLOutput($api_url, 'POST', $finalData);

        $decodedResp = json_decode($curlOutput);

        if ($decodedResp->status == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully added']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }
    }
}
