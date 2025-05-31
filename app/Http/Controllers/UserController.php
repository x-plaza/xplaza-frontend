<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 * Handles user-related functionalities such as changing passwords.
 */
class UserController extends Controller
{
    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * Inject ApiService.
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

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

        $resp = $this->apiService->post('/admin-users/change-password', $bodyData);

        if (($resp['status'] ?? 0) == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully changed']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $resp['message'] ?? 'Change failed']);
        }
    }
}
