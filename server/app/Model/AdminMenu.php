<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\AdminRole;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;

    public function menuInit($role_id = '')
    {
        if ($role_id != 1) {
            $roleInfo = AdminRole::where("role_id", $role_id)->first();
            if ($roleInfo == null) {
                return false;
            }
            $menuId = explode(',', $roleInfo->role_menu);
            if (!empty($menuId)) {
                $menu = AdminMenu::orderBy('display', 'asc')->whereIn('menu_id', $menuId)->get();
            } else {
                return false;
            }
        } else {
            $menu = AdminMenu::orderBy('display', 'asc')->get();
        }
        if ($menu == null) {
            return false;
        }
        $menu = json_decode(json_encode($menu), true);
        $aConnect = array_column($menu, 'name', 'menu_id');
        $aFunc = array_unique(array_column($menu, 'class_func'));
        $aMenu = array();
        foreach ($menu as $key => $value) {
            if ($value['root_id'] == 0) {
                $aMenu[$value['name']] = array();
            }
        }
        foreach ($menu as $key => $value) {
            if (!empty($value) && $value['root_id'] > 0 && isset($aConnect[$value['root_id']])) {
                $aMenu[$aConnect[$value['root_id']]][] = $value;
            }
        }
        return [$aMenu, $aFunc];
    }
}
