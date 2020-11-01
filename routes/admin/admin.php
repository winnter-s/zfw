<?php
// 后台路由

// 路由分组

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    // 登录显示 name 给路由起一个别名
    Route::get('login','LoginController@index')->name('admin.login');
    // 登录处理
    Route::post('login','LoginController@login')->name('admin.login');

    // 后台需要验证才能通过
    Route::group(['middleware'=>['ckadmin'],'as'=>'admin.'], function (){
        // 后台首页显示
        Route::get('index','IndexController@index')->name('index');
        // 欢迎页面
        Route::get('welcome','IndexController@welcome')->name('welcome');
        // 退出
        Route::get('logout','IndexController@logout')->name('logout');

        // ----- 用户管理 -----
        // 用户列表
        Route::get('user/index','UserController@index')->name('user.index');
        // 添加用户显示
        Route::get('user/add','UserController@create')->name('user.create');
        // 添加用户处理
        Route::post('user/index','UserController@store')->name('user.store');
        // 发送邮件
//        Route::get('user/email',function(){
//            \Mail::raw('测试一下发邮件',function(\Illuminate\Mail\Message $message){
//                // 获取回调方法中的形参
//                //dump(func_get_args());
//                // 发送文本邮件
//                // 发给谁
////                $message->to('6692223@qq.com','小张');
////                // 主题
////                $message->subject('测试邮件');
//
//                // 发送富文本文件
//                // 参数1 模板视图
//                // 参数2 传给视图参数
//                \Mail::send('mail.adduser',['user'=>'张三'],function(\Illuminate\Mail\Message $message){
//                    // 发给谁
//                $message->to('6692223@qq.com','小张');
//                // 主题
//                $message->subject('测试邮件');
//                });
//            });
//        });
        //删除用户
        Route::delete('user/del/{id}','UserController@del')->name('user.del');
        // 还原软删除用户
        Route::get('user/restore/{id}','UserController@restore')->name('user.restore');
        // 全选删除
        Route::delete('user/delall','UserController@delall')->name('user.delall');
        // 修改用户 显示
        Route::get('user/edit/{id}','UserController@edit')->name('user.edit');
        // 修改用户 处理
        Route::put('user/edit/{id}','UserController@update')->name('user.edit');
        // 给用户分配权限 角色
        Route::match(['get','post'],'user/role/{user}','UserController@role')->name('user.role');
        // 角色管理
        // 分配权限
        Route::get('role/node/{role}','RoleController@node')->name('role.node');
        Route::post('role/node/{role}','RoleController@nodeSave')->name('role.node');
        // 资源路由
        Route::resource('role','RoleController');

        // 节点管理
        Route::resource('node','NodeController');
        Route::post('article/upfile','ArticleController@upfile')->name('article.upfile');
        // 资源路由
        Route::resource('article','ArticleController');
        // 房源属性
        Route::post('fangattr/upfile','FangattrController@upfile')->name('fangattr.upfile');
        Route::resource('fangattr','FangAttrController');
    });

});
