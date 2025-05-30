<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiAuthenticationsController extends Controller
{
    /**
     * ApiService instance.
     *
     * @var ApiService
     */
    protected $apiService;

    /**
     * Inject ApiService.
     *
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Attempt to log in a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAttempt(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response = $this->apiService->post('/customer-login', [
                'username' => $validated['email'],
                'password' => $validated['password'],
            ]);

            if (empty($response['data']) || empty($response['data']['authentication']) || $response['data']['authentication'] !== true) {
                return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'Invalid credentials']);
            }

            $authData = $response['data'];
            session()->put('auth_user_id', $authData['id']);
            $sessionOthers = Session::get('session_others_array', []);
            $sessionOthers['user_email'] = $authData['email'] ?? $validated['email'];
            $sessionOthers['user_name'] = $authData['name'] ?? '';
            session()->put('session_others_array', $sessionOthers);
            Session::save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully logged in']);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Login failed']);
        }
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registration(Request $request)
    {
        $validated = $request->validate([
            'reg_city' => 'required|string',
            'reg_area' => 'required|string',
            'reg_country' => 'required|string',
            'reg_street_name' => 'required|string',
            'reg_dob' => 'required|date',
            'reg_f_name' => 'required|string',
            'reg_l_name' => 'required|string',
            'reg_house_no' => 'required|string',
            'reg_mobile' => 'required|string',
            'reg_post_code' => 'required|string',
            'reg_email' => 'required|email',
            'reg_password' => 'required|string|min:6',
            'reg_conf_password' => 'required|string|same:reg_password',
            'reg_otp' => 'required|string',
        ]);

        try {
            $bodyData = [
                'area' => $validated['reg_area'],
                'city' => $validated['reg_city'],
                'country' => $validated['reg_country'],
                'date_of_birth' => $validated['reg_dob'],
                'email' => $validated['reg_email'],
                'first_name' => $validated['reg_f_name'],
                'house_no' => $validated['reg_house_no'],
                'last_name' => $validated['reg_l_name'],
                'mobile_no' => $validated['reg_mobile'],
                'otp' => $validated['reg_otp'],
                'password' => $validated['reg_password'],
                'postcode' => $validated['reg_post_code'],
                'salt' => 'test',
                'street_name' => $validated['reg_street_name'],
            ];

            $response = $this->apiService->post('/customer-signup', $bodyData);

            if (empty($response['status']) || $response['status'] != 201) {
                return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'Registration failed']);
            }

            // Auto-login after registration
            $loginResponse = $this->apiService->post('/customer-login', [
                'username' => $validated['reg_email'],
                'password' => $validated['reg_password'],
            ]);
            $shopData = $loginResponse['data'] ?? [];
            session()->put('auth_user_id', $shopData['id'] ?? null);
            $sessionOthers = Session::get('session_others_array', []);
            $sessionOthers['user_email'] = $validated['reg_email'];
            $sessionOthers['user_name'] = $validated['reg_f_name'] . ' ' . $validated['reg_l_name'];
            session()->put('session_others_array', $sessionOthers);
            Session::save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully registered']);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Registration not completed']);
        }
    }

    /**
     * Initialize login (alternative login endpoint).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initLogin(Request $request)
    {
        $validated = $request->validate([
            'login_email' => 'required|email',
            'login_password' => 'required|string',
        ]);

        try {
            $response = $this->apiService->post('/customer-login', [
                'username' => $validated['login_email'],
                'password' => $validated['login_password'],
            ]);
            $authData = $response['data'] ?? [];
            if (empty($authData['authentication']) || $authData['authentication'] !== true) {
                return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'Invalid credentials']);
            }
            $sessionOthers = Session::get('session_others_array', []);
            $sessionOthers['user_email'] = $authData['email'] ?? $validated['login_email'];
            $sessionOthers['user_name'] = $authData['name'] ?? '';
            session()->put('auth_user_id', $authData['id'] ?? null);
            session()->put('session_others_array', $sessionOthers);
            Session::save();

            return response()->json(['responseCode' => 1, 'message' => 'Successfully logged in']);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Invalid credential']);
        }
    }

    /**
     * Log out the current user and clear session data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logOutAttempt()
    {
        session()->put('cart_item_array', []);
        session()->put('session_others_array', null);
        session()->put('selected_shop_id', null);
        session()->put('auth_user_id', null);
        Session::save();

        return redirect('/');
    }
}
