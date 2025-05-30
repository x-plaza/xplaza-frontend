<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\ApiService;

/**
 * Class HomeController
 * Handles the home page and dashboard functionalities.
 */
class HomeController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display the home page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        // Fetch city data
        $city_data = [];
        $response = $this->apiService->get('/cities');
        if (isset($response->data)) {
            $city_data = $response->data;
        }

        // Fetch category data from session or API
        $category_data = Session::get('category_data_array') ? Session::get('category_data_array') : null;
        if ($category_data === null) {
            $response = $this->apiService->get('/categories');
            $category_data = isset($response->data) ? $response->data : [];
            Session::put('category_data_array', $category_data);
        }

        $cubCat = [];

        return view('home', compact('city_data', 'category_data', 'cubCat'));
    }

    /**
     * Return dashboard content as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardContent(Request $request)
    {
        try {
            $shop_id = intval($request->get('shop_id'));
            $response = $this->apiService->post('/dashboard?shop_id=' . $shop_id, []);
            $shop_data = isset($response->data) ? $response->data : [];

            $public_html = strval(view('dashboard_content', compact('shop_data')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'message' => 'No data found']);
        }
    }
}
