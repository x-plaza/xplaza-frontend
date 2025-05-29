<?php

namespace App\Http\Controllers;

use App\Libraries\AclHandler;
use App\Libraries\HandleApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class adminUserController extends Controller
{
    public function userList()
    {
        if (AclHandler::hasAccess('User Creation', 'full') == false) {
            exit('Not access . Recorded this ');
            exit();
        }

        $api_url = env('API_BASE_URL').'/roles';
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $json_resp = json_decode($curlOutput);
        $roles = isset($json_resp->data) ? $json_resp->data : [];

        $api_url = env('API_BASE_URL').'/shops?user_id='.Session::get('userId');
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $json_resp = json_decode($curlOutput);
        $shops = isset($json_resp->data) ? $json_resp->data : [];

        return view('admin_user.admin_user_list', compact('roles', 'shops'));
    }

    public function getList()
    {

        $api_url = env('API_BASE_URL').'/admin-users?user_id='.Session::get('userId');
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);

        $decodedData = json_decode($curlOutput);

        if (isset($decodedData->data) && substr(json_encode($decodedData->data), 0, 1) == '{') {
            $data[] = $decodedData->data;
        } else {
            $data = $decodedData->data;
        }

        return Datatables::of(collect($data))
            ->addColumn('action', function ($data) {
                $action = '';
                if (AclHandler::hasAccess('User Creation', 'update') == true) {
                    $action = '<button type="button" class="btn btn-info btn-xs open_admin_modal" data-admin_id="'.$data->id.'" ><b><i class="fa fa-edit"></i> Edit</b></button> &nbsp;';
                }
                if (AclHandler::hasAccess('User Creation', 'delete') == true) {
                    $action .= ' <button type="button" class="btn btn-danger btn-xs deleteAdmin" data-admin_id="'.$data->id.'"><b><i class="fa fa-trash"></i> Delete</b></button>';
                }

                return $action;
            })
            ->removeColumn('id')
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (AclHandler::hasAccess('User Creation', 'add') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'role_id' => 'required',
            'user_name' => 'required',
            'shop_id' => 'required',
            'confirmation_code' => 'required',
            'name' => 'required',
            'password' => 'required',
            // 'salt'      => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $role_id = $request->get('role_id');
        $shop_id = $request->get('shop_id');
        $name = $request->get('name');
        $password = $request->get('password');
        $user_name = $request->get('user_name');
        $confirmation_code = $request->get('confirmation_code');
        $salt = '123';

        $shopInfo = '';
        $finalString = '';
        if (isset($shop_id) && $shop_id != '') {
            foreach ($shop_id as $id) {
                $shopInfo .= '{"shop_id":'.$id.'},';
            }
            $shopInfo = substr_replace($shopInfo, '', -1);
            $finalString = '['.$shopInfo.']';
        }

        $bodyData = [
            'adminUserShopLinks' => $finalString,
            'confirmation_code' => $confirmation_code,
            'full_name' => $name,
            'password' => $password,
            'role_id' => $role_id,
            'salt' => $salt,
            'user_name' => $user_name,
        ];
        $data = json_encode($bodyData);
        $removedSlash = str_replace('\\', '', $data);
        $removedStartClose = str_replace(':"[', ':[', $removedSlash);
        $finalData = str_replace(']"', ']', $removedStartClose);

        $api_url = env('API_BASE_URL').'/admin-users';
        $curlOutput = HandleApi::getCURLOutput($api_url, 'POST', $finalData);

        $decodedResp = json_decode($curlOutput);

        if ($decodedResp->status == 201) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully added']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }
    }

    public function getOtp(Request $request)
    {
        if (AclHandler::hasAccess('User Creation', 'add') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'user_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $user_name = $request->get('user_name');

        $api_url = env('API_BASE_URL').'/confirmation-tokens?username='.$user_name;
        $curlOutput = HandleApi::getCURLOutput($api_url, 'POST', []);

        $decodedResp = json_decode($curlOutput);

        if ($decodedResp->status == 201) {
            return response()->json(['responseCode' => 1, 'message' => $decodedResp->message]);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminInfo(Request $request)
    {
        $rules = [
            'admin_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $admin_id = $request->get('admin_id');

        $api_url = env('API_BASE_URL').'/roles';
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $json_resp = json_decode($curlOutput);
        $roles = isset($json_resp->data) ? $json_resp->data : [];

        $api_url = env('API_BASE_URL').'/shops?user_id='.Session::get('userId');
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $json_resp = json_decode($curlOutput);
        $shops = isset($json_resp->data) ? $json_resp->data : [];

        $api_url = env('API_BASE_URL').'/admin-users/'.intval($admin_id);
        $curlOutput = HandleApi::getCURLOutput($api_url, 'GET', []);
        $decodedData = json_decode($curlOutput);
        $admin_data = isset($decodedData->data) ? $decodedData->data : [];

        $shopArr = [];
        foreach ($admin_data->shopList as $shop) {
            $shopArr[] = $shop->shop_id;
        }

        $public_html = strval(view('admin_user.modal_data', compact('admin_data', 'roles', 'shops', 'shopArr')));

        return response()->json(['responseCode' => 1, 'html' => $public_html, 'message' => 'Successfully fetches']);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdmin(Request $request)
    {

        if (AclHandler::hasAccess('User Creation', 'update') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }
        $rules = [
            'edit_admin_id' => 'required',
            'edit_role_id' => 'required',
            'edit_name' => 'required',
            'edit_user_name' => 'required',
            'edit_shop_id' => 'required',
            'edit_password' => 'required',
            // 'edit_salt'      => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $admin_id = $request->get('edit_admin_id');
        $role_id = $request->get('edit_role_id');
        $shop_id = $request->get('edit_shop_id');
        $name = $request->get('edit_name');
        $user_name = $request->get('edit_user_name');
        $password = $request->get('edit_password');
        $salt = 456;

        $shopInfo = '';
        $finalString = '';
        if (isset($shop_id) && $shop_id != '') {
            foreach ($shop_id as $id) {
                $shopInfo .= '{"shop_id":'.$id.'},';
            }
            $shopInfo = substr_replace($shopInfo, '', -1);
            $finalString = '['.$shopInfo.']';
        }

        $bodyData = [
            'adminUserShopLinks' => $finalString,
            'full_name' => $name,
            'id' => $admin_id,
            'password' => $password,
            'role_id' => $role_id,
            'salt' => $salt,
            'user_name' => $user_name,
        ];
        $data = json_encode($bodyData);
        $removedSlash = str_replace('\\', '', $data);
        $removedStartClose = str_replace(':"[', ':[', $removedSlash);
        $finalData = str_replace(']"', ']', $removedStartClose);

        $api_url = env('API_BASE_URL').'/admin-users';
        $curlOutput = HandleApi::getCURLOutput($api_url, 'PUT', $finalData);

        $decodedResp = json_decode($curlOutput);
        if ($decodedResp->status == 200) {
            return response()->json(['responseCode' => 1, 'message' => 'Successfully updated']);
        } else {
            return response()->json(['responseCode' => 0, 'message' => $decodedResp->message]);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAdmin(Request $request)
    {
        if (AclHandler::hasAccess('User Creation', 'delete') == false) {
            return response()->json(['responseCode' => 0, 'message' => 'Not access . Recorded this']);
        }

        $rules = [
            'admin_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['responseCode' => 0, 'message' => 'Please fill up required field']);
        }

        $api_url = env('API_BASE_URL').'/admin-users/'.intval($request->get('admin_id'));
        $curlOutput = HandleApi::getCURLOutput($api_url, 'DELETE', []);

        $decodedData = json_decode($curlOutput);

        return response()->json(['responseCode' => 1, 'message' => 'Successfully Deleted']);

    }
}
