<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ForgotPasswordController
 * Handles password reset functionalities.
 */
class ForgotPasswordController extends Controller
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
     * Send OTP to user for password reset.
     */
    public function getOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }
        $user_name = $request->get('user_name');
        $response = $this->apiService->post('/login/send-otp', ['username' => $user_name]);
        if (($response['status'] ?? 0) == 201) {
            return response()->json(['responseCode' => 1, 'message' => $response['message'] ?? 'OTP sent']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'OTP failed']);
        }
    }

    /**
     * Set new password after OTP validation.
     */
    public function setNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'otp_code' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }
        $user_name = $request->get('user_name');
        $otp_code = $request->get('otp_code');
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('confirm_password');
        if ($new_password != $confirm_password) {
            return response()->json(['responseCode' => 0, 'message' => 'Confirm password does not match']);
        }
        $otpResp = $this->apiService->post('/login/validate-otp', [
            'OTP' => $otp_code,
            'username' => $user_name,
        ]);
        if (($otpResp['status'] ?? 0) != 200) {
            return response()->json(['responseCode' => 0, 'message' => $otpResp['message'] ?? 'OTP validation failed']);
        }
        $changePassResp = $this->apiService->post('/login/change-password', [
            'newPassword' => $new_password,
            'username' => $user_name,
        ]);
        if (($changePassResp['status'] ?? 0) == 200) {
            return response()->json(['responseCode' => 1, 'message' => $changePassResp['message'] ?? 'Password changed']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $changePassResp['message'] ?? 'Password change failed']);
        }
    }

    /**
     * Set new password for forgot password (customer login).
     */
    public function setForgotPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reset_password' => 'required',
            'reset_conf_password' => 'required',
            'reset_otp' => 'required',
            'reset_email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }
        $user_name = $request->get('reset_email');
        $otp_code = $request->get('reset_otp');
        $new_password = $request->get('reset_password');
        $confirm_password = $request->get('reset_conf_password');
        if ($new_password != $confirm_password) {
            return response()->json(['responseCode' => 0, 'message' => 'Confirm password does not match']);
        }
        $otpResp = $this->apiService->post('/customer-login/validate-otp', [
            'OTP' => $otp_code,
            'username' => $user_name,
        ]);
        if (($otpResp['status'] ?? 0) != 200) {
            return response()->json(['responseCode' => 0, 'message' => $otpResp['message'] ?? 'OTP validation failed']);
        }
        $changePassResp = $this->apiService->post('/customer-login/change-password', [
            'newPassword' => $new_password,
            'username' => $user_name,
        ]);
        if (($changePassResp['status'] ?? 0) == 200) {
            return response()->json(['responseCode' => 1, 'message' => $changePassResp['message'] ?? 'Password changed']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $changePassResp['message'] ?? 'Password change failed']);
        }
    }
}
