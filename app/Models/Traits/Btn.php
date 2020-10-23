<?php


namespace App\Models\Traits;


trait Btn
{
    public function editBtn(string $route)
    {
        if(auth()->user()->username != config('rbac.super') && !in_array($route,request()->auths)){
            return '';
        }
        return '<a href="'.route($route,$this).'" class="label label-secondary radius">修改</a>';
    }

    public function deleteBtn(string $route)
    {
        if(auth()->user()->username != config('rbac.super') && !in_array($route,request()->auths)){
            return '';
        }
        return '<a href="'.route($route,$this).'" class="label label-danger radius delbtn">删除</a>';
    }
}
