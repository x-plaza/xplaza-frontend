<?php

namespace App\Http\Controllers;

use App\Libraries\AclHandler;
use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (AclHandler::hasAccess('Product', 'full') == false) {
            exit('Not access . Recorded this ');
            exit();
        }

        return view('item.add_item');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function itemList()
    {
        if (AclHandler::hasAccess('Product', 'full') == false) {
            exit('Not access . Recorded this ');
            exit();
        }

        $brand_api_url = env('API_BASE_URL').'/brands';
        $brandCurlOutput = HandleApi::getCURLOutput($brand_api_url, 'GET', []);
        $brand_json_resp = json_decode($brandCurlOutput);
        $brands = isset($brand_json_resp->data) ? $brand_json_resp->data : [];

        $category_api_url = env('API_BASE_URL').'/categories';
        $categoryCurlOutput = HandleApi::getCURLOutput($category_api_url, 'GET', []);
        $category_json_resp = json_decode($categoryCurlOutput);
        $categories = isset($category_json_resp->data) ? $category_json_resp->data : [];

        $shop_api_url = env('API_BASE_URL').'/shops?user_id='.Session::get('userId');
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $shops = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        $shop_api_url = env('API_BASE_URL').'/currencies';
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $currencies = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        $shop_api_url = env('API_BASE_URL').'/product-variation-types';
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $prodvartypes = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        return view('item.item_list', compact('brands', 'categories', 'shops', 'currencies', 'prodvartypes'));
    }

    public function getList()
    {
        $api_url = env('API_BASE_URL').'/products?user_id='.Session::get('userId');
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);

        $decodedData = json_decode($curlOutput);
        $data = $decodedData->data;

        return Datatables::of(collect($data))
            ->addColumn('action', function ($data) {
                $action = '';
                if (AclHandler::hasAccess('Product', 'view') == true) {
                    $action = '<button type="button" class="btn btn-primary btn-xs open_item_view_modal" data-item_id="'.$data->id.'" ><b><i class="fa fa-eye"></i> View</b></button> &nbsp;';
                }
                if (AclHandler::hasAccess('Product', 'update') == true) {
                    $action .= '<button type="button" class="btn btn-info btn-xs open_item_modal" data-item_id="'.$data->id.'" ><b><i class="fa fa-edit"></i> Edit</b></button> &nbsp;';
                }
                if (AclHandler::hasAccess('Product', 'delete') == true) {
                    $action .= ' <button type="button" class="btn btn-danger btn-xs deleteItem" data-item_id="'.$data->id.'"><b><i class="fa fa-trash"></i> Delete</b></button>';
                }

                return $action;
            })
            ->editColumn('image', function ($data) {
                $imageName = isset($data->productImageList[0]->name) ? $data->productImageList[0]->name : '';

                return "<center><img src='/item_image/".$imageName."' style='width: 70px; height: 70px;'></center>";
            })
            ->removeColumn('id')
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (AclHandler::hasAccess('Product', 'add') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'item_name' => 'required',
            'item_image' => 'required',
            'quantity' => 'required',
            'description' => 'required',
            'shop_id' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'currency_id' => 'required',
            'product_var_type_id' => 'required',
            'product_var_type_value' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // return response()->json( ['responseCode'=>0,'message'=>'Please fill up required field']);
        }

        $item_name = $request->get('item_name');
        $item_image = $request->get('item_image');
        $description = $request->get('description');
        $shop_id = $request->get('shop_id');
        $quantity = $request->get('quantity');
        $brand_id = $request->get('brand_id');
        $category_id = $request->get('category_id');
        $buying_price = $request->get('buying_price');
        $selling_price = $request->get('selling_price');
        $currency_id = $request->get('currency_id');
        $product_var_type_id = $request->get('product_var_type_id');
        $product_var_type_value = $request->get('product_var_type_value');

        [$type, $data] = explode(';', $item_image);
        [, $data] = explode(',', $data);
        $image = Image::make(base64_decode($data))->encode('jpg');
        $imageName = date('ymdhis').'.jpg';
        $image->save(public_path().'/item_image/'.$imageName);

        $productImage[] = [
            'name' => $imageName,
            'path' => '/item_image/'.$imageName,
            'product_id' => 0,
        ];

        $bodyData = [
            'brand_id' => $brand_id,
            'buying_price' => $buying_price,
            'category_id' => $category_id,
            'quantity' => $quantity,
            'currency_id' => $currency_id,
            'description' => $description,
            'name' => $item_name,
            'productImage' => $productImage,
            'product_var_type_id' => $product_var_type_id,
            'product_var_type_value' => $product_var_type_value,
            'selling_price' => $selling_price,
            'shop_id' => $shop_id,
        ];
        $fieldData = json_encode($bodyData);

        $api_url = env('API_BASE_URL').'/products';
        $curlOutputMain = HandleApi::getCURLOutput($api_url, 'POST', $fieldData);

        $decodedResp = json_decode($curlOutputMain);
        if ($decodedResp->status == 201) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully added']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemInfo(Request $request)
    {
        $rules = [
            'item_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $item_id = $request->get('item_id');

        $brand_api_url = env('API_BASE_URL').'/brands';
        $brandCurlOutput = HandleApi::getCURLOutput($brand_api_url, 'GET', []);
        $brand_json_resp = json_decode($brandCurlOutput);
        $brands = isset($brand_json_resp->data) ? $brand_json_resp->data : [];

        $category_api_url = env('API_BASE_URL').'/categories';
        $categoryCurlOutput = HandleApi::getCURLOutput($category_api_url, 'GET', []);
        $category_json_resp = json_decode($categoryCurlOutput);
        $categories = isset($category_json_resp->data) ? $category_json_resp->data : [];

        $shop_api_url = env('API_BASE_URL').'/shops?user_id='.Session::get('userId');
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $shops = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        $api_url = env('API_BASE_URL').'/products/'.intval($item_id);
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $decodedData = json_decode($curlOutput);
        $item_data = isset($decodedData->data) ? $decodedData->data : [];

        $api_url = env('API_BASE_URL').'/products/'.intval($item_id);
        $curlOutputMain = HandleApi::getCURLOutput($api_url, 'GET', []);
        $decodedDataForItem = json_decode($curlOutputMain);
        $itemInfo = $decodedDataForItem->data;

        $shop_api_url = env('API_BASE_URL').'/currencies';
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $currencies = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        $shop_api_url = env('API_BASE_URL').'/product-variation-types';
        $shopCurlOutput = HandleApi::getCURLOutput($shop_api_url, 'GET', []);
        $shop_json_resp = json_decode($shopCurlOutput);
        $prodvartypes = isset($shop_json_resp->data) ? $shop_json_resp->data : [];

        if (isset($itemInfo->productImageList[0]->name)) {
            $image_url = $itemInfo->productImageList[0]->name;
            $image_id = $itemInfo->productImageList[0]->id;

        } else {
            $image_id = '';
            $image_url = '';
        }

        $public_html = strval(view('item.modal_data', compact('item_data', 'brands', 'categories', 'shops', 'itemInfo', 'image_url', 'currencies', 'prodvartypes', 'image_id')));

        return response()->json(['responseCode' => 1, 'html' => $public_html, 'message' => 'Successfully fetches']);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemInfoView(Request $request)
    {
        $rules = [
            'item_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $item_id = $request->get('item_id');

        $api_url = env('API_BASE_URL').'/products/'.intval($item_id);
        $curlOutputMain = HandleApi::getCURLOutput($api_url, 'GET', []);
        $decodedDataForItem = json_decode($curlOutputMain);
        $itemInfo = $decodedDataForItem->data;

        if (isset($itemInfo->productImageList[0]->name)) {
            $image_url = $itemInfo->productImageList[0]->name;
            $image_id = $itemInfo->productImageList[0]->id;

        } else {
            $image_id = '';
            $image_url = '';
        }

        $public_html = strval(view('item.modal_data_view', compact('itemInfo', 'image_url')));

        return response()->json(['responseCode' => 1, 'html' => $public_html, 'message' => 'Successfully fetches']);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateItem(Request $request)
    {
        if (AclHandler::hasAccess('Product', 'update') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'item_id' => 'required',
            'quantity' => 'required',
            'item_name' => 'required',
            'description' => 'required',
            'shop_id' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'currency_id' => 'required',
            'product_var_type_id' => 'required',
            'product_var_type_value' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        }

        $item_id = $request->get('item_id');
        $quantity = $request->get('quantity');
        $item_name = $request->get('item_name');
        $item_image = $request->get('item_image');
        $description = $request->get('description');
        $shop_id = $request->get('shop_id');
        $brand_id = $request->get('brand_id');
        $category_id = $request->get('category_id');
        $buying_price = $request->get('buying_price');
        $selling_price = $request->get('selling_price');
        $currency_id = $request->get('currency_id');
        $product_var_type_id = $request->get('product_var_type_id');
        $product_var_type_value = $request->get('product_var_type_value');

        $productImage = [];
        if (isset($item_image)) {
            [$type, $data] = explode(';', $item_image);
            [, $data] = explode(',', $data);
            $image = Image::make(base64_decode($data))->encode('jpg');
            $imageName = date('ymdhis').'.jpg';
            $image->save(public_path().'/item_image/'.$imageName);

            $productImage[] = [
                'name' => $imageName,
                'path' => 'item_image/'.$imageName,
                'product_id' => $item_id,
            ];
        }

        $bodyData = [
            'id' => $item_id,
            'brand_id' => $brand_id,
            'quantity' => $quantity,
            'buying_price' => $buying_price,
            'category_id' => $category_id,
            'currency_id' => $currency_id,
            'description' => $description,
            'name' => $item_name,
            'productImage' => $productImage,
            'product_var_type_id' => $product_var_type_id,
            'product_var_type_value' => $product_var_type_value,
            'selling_price' => $selling_price,
            'shop_id' => $shop_id,
        ];
        $fieldData = json_encode($bodyData);

        $api_url = env('API_BASE_URL').'/products';
        $curlOutputMain = HandleApi::getCURLOutput($api_url, 'PUT', $fieldData);

        $decodedResp = json_decode($curlOutputMain);
        if ($decodedResp->status == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem(Request $request)
    {

        if (AclHandler::hasAccess('Product', 'delete') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'item_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $api_url = env('API_BASE_URL').'/products/'.intval($request->get('item_id'));
        $curlOutput = HandleApi::getCURLOutput($api_url, 'DELETE', []);

        $decodedData = json_decode($curlOutput);

        if ($decodedData->status == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedData->message]);
        }
    }
}
