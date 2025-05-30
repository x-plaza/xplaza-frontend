<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class AuthController
 * Handles user authentication functionalities.
 */
class CheckoutController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        $session_cart_item_array = Session::get('cart_item_array', []);
        if (count($session_cart_item_array) == 0) {
            return redirect('/');
        }

        $cityResponse = $this->apiService->get('/cities');
        $city_data = $cityResponse['data'] ?? [];

        $category_data = Session::get('category_data_array');
        if ($category_data == null) {
            $categoryResponse = $this->apiService->get('/categories');
            $category_data = $categoryResponse['data'] ?? [];
            session()->put('category_data_array', $category_data);
            Session::save();
        }

        $cubCat = [];
        $totalPrice = 0;
        $sessionData = Session::get('cart_item_array');
        $finalItemArray = $sessionData ?? [];
        foreach ($finalItemArray as $item) {
            $totalPrice += $item['item_unit_price'] * $item['quantity'];
        }
        $deliveryCost = $this->apiService->get('/delivery-cost', ['total_price' => $totalPrice]);
        $authId = Session::get('auth_user_id');

        return view('checkout', compact('city_data', 'category_data', 'cubCat', 'deliveryCost', 'authId'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {
        $auth_user_id = Session::get('auth_user_id');
        if (! isset($auth_user_id)) {
            return response()->json(['responseCode' => 5, 'message' => 'Please sign up first']);
        }
        $delivery_schedule_id = $request->get('delivery_schedule_id');
        $delivery_schedule_text = $request->get('delivery_schedule_text');
        $customer_address = $request->get('customer_address');
        $coupon_number = $request->get('coupon_number');
        $customer_mobile = $request->get('customer_mobile');
        $delivery_date = $request->get('delivery_date');
        $order_additional_info = $request->get('order_additional_info');

        if (! isset($delivery_schedule_text) || ! isset($customer_address) || ! isset($customer_mobile)) {
            return response()->json(['responseCode' => 0, 'message' => 'Input data missing']);
        }

        $session_cart_item_array = Session::get('cart_item_array', []);
        $session_others_array = Session::get('session_others_array', []);
        if (count($session_cart_item_array) == 0) {
            return response()->json(['responseCode' => 0, 'message' => 'Cart is empty']);
        }

        $itemData = [];
        $totalPrice = 0;
        foreach ($session_cart_item_array as $item) {
            $totalPrice += $item['item_unit_price'] * $item['quantity'];
            $itemData[] = [
                'currency_id' => intval($item['currency_id']),
                'product_id' => intval($item['product_id']),
                'item_name' => $item['item_name'],
                'item_image' => $item['item_image'],
                'item_category' => $item['item_category'],
                'item_var_type_name' => $item['item_var_type_name'],
                'item_var_type_value' => intval($item['item_var_type_value']),
                'quantity' => intval($item['quantity']),
                'quantity_type' => $item['quantity_type'],
            ];
        }

        $customer_email = $session_others_array['user_email'] ?? '';
        $customer_full_name = $session_others_array['user_name'] ?? '';

        $finalOrderData = [
            'shop_id' => intval($session_others_array['shop_id'] ?? 0),
            'shop_name' => $session_others_array['shop_name'] ?? '',
            'customer_id' => intval($auth_user_id),
            'customer_name' => trim($customer_full_name),
            'mobile_no' => trim($customer_mobile),
            'delivery_address' => trim($customer_address),
            'additional_info' => $order_additional_info,
            'delivery_schedule_start' => explode('-', $delivery_schedule_id)[0],
            'delivery_schedule_end' => explode('-', $delivery_schedule_id)[1],
            'received_time' => date('Y-m-d', strtotime($delivery_date)).'T15:14:26.637Z',
            'date_to_deliver' => date('Y-m-d', strtotime($delivery_date)).'T15:14:26.637Z',
            'status_id' => 1,
            'currency_id' => 1,
            'coupon_id' => null,
            'coupon_code' => $coupon_number,
            'delivery_cost_id' => intval($session_others_array['delivery_cost_id'] ?? 0),
            'orderItemList' => $itemData,
        ];

        $response = $this->apiService->post('/orders', $finalOrderData);
        $orderResp = isset($response['data']) ? json_decode($response['data']) : null;
        $grandTotalPrice = $orderResp->grand_total_price ?? '';
        $invoice = $orderResp->invoice_number ?? '';
        if (! isset($response['status']) || $response['status'] != 201) {
            return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'Order failed', 'Total_price' => 'R '.$grandTotalPrice, 'invoice' => $invoice]);
        }

        session()->put('cart_item_array', []);
        Session::save();

        return response()->json(['responseCode' => 1, 'message' => 'Order placed successfully', 'Total_price' => 'R '.$grandTotalPrice, 'invoice' => $invoice]);
    }

    public function validateCoupon(Request $request)
    {
        $coupon_number = $request->get('coupon_number');
        $delivery_cost_section_hidden = floatval($request->get('delivery_cost_section_hidden'));
        if (! isset($coupon_number) || $coupon_number == null) {
            return response()->json(['responseCode' => 2, 'message' => 'Coupon number required']);
        }

        $totalPrice = 0;
        $sessionData = Session::get('cart_item_array');
        $finalItemArray = $sessionData ?? [];
        foreach ($finalItemArray as $item) {
            $totalPrice += $item['item_unit_price'] * $item['quantity'];
        }

        $session_others_array = Session::get('session_others_array', []);
        $shopId = intval($session_others_array['shop_id'] ?? 0);

        $response = $this->apiService->post('/coupons/validate-coupon', [
            'coupon_code' => $coupon_number,
            'net_order_amount' => $totalPrice,
            'shop_id' => $shopId,
        ]);

        if (! isset($response['status']) || $response['status'] != 200) {
            return response()->json(['responseCode' => 0, 'message' => $response['message'] ?? 'Coupon validation failed', 'final_amount' => round($totalPrice + $delivery_cost_section_hidden, 2)]);
        }

        $finalAmount = floatval($totalPrice) - floatval($response['data'] ?? 0);
        $message = floatval($response['data'] ?? 0);

        return response()->json(['responseCode' => 1, 'final_amount' => round($finalAmount + $delivery_cost_section_hidden, 2), 'message' => $message]);
    }

    public function deliveryTimeSlot(Request $request)
    {
        $deliveryDate = $request->get('delivery_date');
        $selectedDay = date('D', strtotime($deliveryDate));
        $deliverySchedule = $this->apiService->get('/delivery-schedule')['data'] ?? [];

        $filteredArray = [];
        foreach ($deliverySchedule as $data) {
            $subData = [];
            if (strtoupper($data['day_name']) == strtoupper($selectedDay)) {
                if (date('D') == $selectedDay) {
                    if (intval(date('H')) < intval(substr(explode('-', $data['day_slot'])[1], 0, 2))) {
                        $subData['id'] = $data['schedule_id'];
                        $subData['day_slot'] = $data['day_slot'];
                    } else {
                        continue;
                    }
                } else {
                    $subData['id'] = $data['schedule_id'];
                    $subData['day_slot'] = $data['day_slot'];
                }
            } else {
                continue;
            }
            $filteredArray[] = $subData;
        }

        if (count($filteredArray) == 0) {
            return response()->json(['responseCode' => 2, 'schedule_data' => $filteredArray]);
        }

        return response()->json(['responseCode' => 1, 'schedule_data' => $filteredArray]);
    }
}
