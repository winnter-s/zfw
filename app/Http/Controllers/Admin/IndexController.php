<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    // 后台首页显示
    public function index()
    {
        return view('admin.index.index');
    }

    // 欢迎页面
    public function welcome()
    {
        return view('admin.index.welcome');
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
