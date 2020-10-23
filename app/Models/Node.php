<?php

namespace App\Models;

class Node extends Base
{
    // 修改器
    public function setRouteNameAttribute($value)
    {
        $this->attributes['route_name'] = empty($value) ? '' : $value;
    }

    // 访问器
    public function getMenuAttribute()
    {
        return $this->is_menu == '1' ?
            '<span class="label label-success radius">是</span>' :
            '<span class="label label-danger radius">否</span>' ;
    }

    // 获取全部的数据
    public function getAllList()
    {
        $data = self::get()->toArray();
        return $this->treeLevel($data);
    }

    // 获取层级数据

    /**
     * @param array $allow_node
     * @return array
     */
    public function treeData($allow_node)
    {
        $query = Node::where('is_menu','1');
        if (is_array($allow_node)){
            // 其他权限
            $query->whereIn('id',array_keys($allow_node));
        }
        // 超级管理员
        $menuData = $query->get()->toArray();


        return $this->subTree($menuData);
    }
}
