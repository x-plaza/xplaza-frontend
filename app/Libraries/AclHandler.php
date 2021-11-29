<?php


namespace App\Libraries;


use Illuminate\Support\Facades\Session;

class AclHandler
{
    public static function hasAccess($module = null,$action = 'dummy'){


        $sessionDataPermission = session::get('permissions');
        $allPermission = isset($sessionDataPermission) ? session::get('permissions') : null;

        if ($allPermission == null) {return false;}

        $specificModulePermission = '';
        foreach ($allPermission as $permission){
            if(isset($permission->menu_name) && $permission->menu_name == $module){
                $specificModulePermission = $permission->acl;
                break;
            }
        }

        if ($specificModulePermission == '') {return false;}

        $actionPermission = isset($specificModulePermission->$action) ? $specificModulePermission->$action : null;

        if ($actionPermission == null) {return false;}

        return $actionPermission;

    }


    public static function getUserName(){

        // return true;
        $authData = session::get('authData');
        $userInfo = isset($authData) ? session::get('authData') : null;

        if ($userInfo == null) {return '';}

        $userName = isset($userInfo->user_name) ? $userInfo->user_name : '';

        return $userName;

    }

}