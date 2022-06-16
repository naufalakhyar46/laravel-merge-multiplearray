<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenu()
    {
        $menu = new \App\Models\Menu;
        $menuList = $menu->tree();
        return json_decode($menuList, true);
        return view('index')->with('menulist', $menuList);
    }

    public function getMenu2()
    {
        $parent_menu = [];
        $sub_menu = [];
        $menu = new \App\Models\Menu;
        $item = $menu->get()->toArray();
        foreach ($item as $key => $value) {
            $obj = (object) $value;
            $parent_menu[$key]['id'] = $obj->id;
            $parent_menu[$key]['title'] = $obj->menu_title;
            $parent_menu[$key]['parent_id'] = $obj->parent_id;
            $parent_menu[$key]['link'] = $obj->slug;
            $parent_menu[$key]['children'] = [];
        }
        $this->get_children($parent_menu,$sub_menu,0);

        return json_decode(json_encode($sub_menu),true);
    }

    public function get_children($in, $out, $parent)
    {
        foreach ($in as $key=>$item) {
            if ($parent == $item["parent_id"]){
                $out[] = $item;
                unset($in[$key]);
                $this->get_children($in, $out[count($out)-1]["children"], $item["id"]);
            }
        }
    }
}
