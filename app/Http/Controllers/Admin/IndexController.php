<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fang;
use App\Models\Node;

class IndexController extends Controller
{
    // 后台首页显示
    public function index()
    {

        $auth = session('admin.auth');
        // 读取菜单
        $menuData = (new Node())->treeData($auth);



        // 指定模板 视图
        return view('admin.index.index',compact('menuData'));
    }

    // 欢迎页面
    public function welcome()
    {
        $data = (new Fang())->fangStatusCount();
        return view('admin.index.welcome',$data);
    }

    // 退出
    public function logout()
    {
        // 用户退出 清空 session
        auth()->logout();
        // 跳转 with 带提示 闪存
        return redirect(route('admin.login'))->with('success','请重新登录');
    }
}
