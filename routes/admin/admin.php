<?php
// 后台路由

// 路由分组

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    // 登录显示 name 给路由起一个别名
    Route::get('login','LoginController@index')->name('admin.login');
    // 登录处理
    Route::post('login','LoginController@login')->name('admin.login');
    // 后台首页显示
    Route::get('index','IndexController@index')->name('admin.index');
    // 欢迎页面
    Route::get('welcome','IndexController@welcome')->name('admin.welcome');
    // 退出
    Route::get('logout','IndexController@logout')->name('admin.logout');
});
