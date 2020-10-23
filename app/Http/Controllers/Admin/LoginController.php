<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 构造方法
//    public function __construct()
//    {
//        $this->middleware(['ckadmin:longin']);
//    }

    // 登录显示
    public function index()
    {
        // 判断用户是否已经登陆过
        if(auth()->check()){
            return redirect(route('admin.index'));
        }

        return view('admin.login.login');
    }

    // 登录 别名 admin.login 根据别名生成 url router(别名);
    public function login(Request $request)
    {
        // 表单验证
        $post = $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // 登录
        $bool = auth()->attempt($post);
        // 判断是否登录成功
        if($bool){ // 登录成功
            // 判断一下是否是超级管理员
            if (config('rbac.super') != $post['username'])
            {
                // auth()->user() 返回当前登录的用户模型对象 存储在 session 中
                // laravel 默认 session 是存储在文件中 以后可以优化到 memcached redis
                $userModel = auth()->user();
                $roleModel = $userModel->role;
                $nodeArr = $roleModel->nodes()->pluck('route_name','id')->toArray();
                // 权限保持到 session
                session(['admin.auth'=>$nodeArr]);
            } else {
                session(['admin.auth' => true]);
            }

            // 跳转以后页面
            return redirect(route('admin.index'));
        }
        // withErrors 把信息写入到验证错误提示中 是特殊的 session ,在 laravel 中叫闪存
        // 闪存 从设置好之后 只能在第一个 http 请求中获取到数据 , 以后就没有了
        return redirect(route('admin.login'))->withErrors(['error'=>'登录失败']);
    }

}
