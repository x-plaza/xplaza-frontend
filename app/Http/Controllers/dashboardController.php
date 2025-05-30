<?php

namespace App\Http\Controllers;

use App\Libraries\HandleApi;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display the dashboard view with all required data.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $city_data = $this->apiService->get('/cities');
            $location_data = $this->apiService->get('/locations');
            $shop_data = $this->apiService->get('/shops?user_id=8');
            $category_data = $this->apiService->get('/categories');

            $cubCat = [];
            foreach ($category_data as $category) {
                if ($category->parent_category_id == 0) {
                    continue;
                }
                $cubCat[] = [
                    'category_id' => $category->id,
                    'parent' => $category->parent_category_id,
                    'name' => $category->name,
                ];
            }

            return view('my_dashboard', compact('city_data', 'location_data', 'shop_data', 'category_data', 'cubCat'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['message' => 'Dashboard data could not be loaded.'], 500);
        }
    }

    /**
     * Fetch order list for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myOrderList(Request $request)
    {
        $auth_user_id = Session::get('auth_user_id') ?? null;
        if (!$auth_user_id) {
            return response()->json(['responseCode' => 0, 'message' => 'User not authenticated']);
        }
        try {
            $product_data = $this->apiService->get('/orders/by-customer?customer_id=' . intval($auth_user_id));
            $public_html = strval(view('home_content.my_order', compact('product_data')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Could not fetch order list.']);
        }
    }

    /**
     * Fetch profile data for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myProfile(Request $request)
    {
        $auth_user_id = Session::get('auth_user_id') ?? null;
        $session_others_array = Session::get('session_others_array') ?? null;
        if (!$auth_user_id || !$session_others_array) {
            return response()->json(['responseCode' => 0, 'message' => 'User not authenticated']);
        }
        try {
            $profile_data = $this->apiService->get('/customers/' . intval($auth_user_id));
            $email = $session_others_array['user_email'];
            $public_html = strval(view('home_content.my_profile', compact('profile_data', 'email')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Could not fetch profile data.']);
        }
    }

    /**
     * Update the profile for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'postcode' => 'required|string|max:20',
            'street_name' => 'required|string|max:255',
            'house_no' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);
        $auth_user_id = Session::get('auth_user_id') ?? null;
        $session_others_array = Session::get('session_others_array') ?? null;
        if (!$auth_user_id || !$session_others_array) {
            return response()->json(['responseCode' => 0, 'message' => 'User not authenticated']);
        }
        $bodyData = [
            'id' => $auth_user_id,
            'area' => $validated['area'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'date_of_birth' => isset($validated['date_of_birth']) ? date(DATE_ISO8601, strtotime($validated['date_of_birth'])) : '1990-04-18T20:33:36.052Z',
            'email' => $session_others_array['user_email'],
            'first_name' => $validated['first_name'],
            'house_no' => $validated['house_no'] ?? null,
            'last_name' => $validated['last_name'],
            'mobile_no' => $validated['mobile_no'],
            'postcode' => $validated['postcode'],
            'street_name' => $validated['street_name'],
        ];
        try {
            $decodedResp = $this->apiService->put('/customers', $bodyData);
            if (isset($decodedResp->status) && $decodedResp->status == 200) {
                return response()->json(['responseCode' => 1, 'message' => 'Successfully updated']);
            } else {
                $msg = isset($decodedResp->message) ? $decodedResp->message : 'Update failed.';
                return response()->json(['responseCode' => 0, 'message' => $msg]);
            }
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'Could not update profile.']);
        }
    }
}
