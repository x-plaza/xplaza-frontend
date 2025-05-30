<?php

namespace App\Http\Controllers;

use App\Libraries\AclHandler;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    /**
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (AclHandler::hasAccess('Product', 'full') == false) {
            exit('Not access . Recorded this ');
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
        }

        $brands = $this->apiService->get('/brands');
        $categories = $this->apiService->get('/categories');
        $shops = $this->apiService->get('/shops', ['user_id' => Session::get('userId')]);
        $currencies = $this->apiService->get('/currencies');
        $prodvartypes = $this->apiService->get('/product-variation-types');

        return view('item.item_list', compact('brands', 'categories', 'shops', 'currencies', 'prodvartypes'));
    }

    public function getList()
    {
        $data = $this->apiService->get('/products', ['user_id' => Session::get('userId')]);

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

        $resp = $this->apiService->post('/products', $bodyData);

        if (($resp['status'] ?? 0) == 201) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully added']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $resp['message'] ?? 'Add failed']);
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

        $brands = $this->apiService->get('/brands');
        $categories = $this->apiService->get('/categories');
        $shops = $this->apiService->get('/shops', ['user_id' => Session::get('userId')]);
        $item_data = $this->apiService->get('/products/' . intval($item_id));
        $itemInfo = $this->apiService->get('/products/' . intval($item_id));
        $currencies = $this->apiService->get('/currencies');
        $prodvartypes = $this->apiService->get('/product-variation-types');

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

        $itemInfo = $this->apiService->get('/products/' . intval($item_id));

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

        $resp = $this->apiService->put('/products', $bodyData);

        if (($resp['status'] ?? 0) == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $resp['message'] ?? 'Update failed']);
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

        $resp = $this->apiService->delete('/products/' . intval($request->get('item_id')));

        if (($resp['status'] ?? 0) == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully deleted']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $resp['message'] ?? 'Delete failed']);
        }
    }
}
