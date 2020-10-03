<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    // 设置添加的字段 create 添加数据有效
    // 拒绝不添加的字段
    protected $guarded = [];

    // 隐藏字段
    protected $hidden = ['password'];
}
