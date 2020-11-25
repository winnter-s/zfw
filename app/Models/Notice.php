<?php

namespace App\Models;

use App\Observers\NoticeObserver;

class Notice extends Base
{
    // 使用 laravel 提供模型类初始化方法 方法中最早执行的
    protected static function boot()
    {
        parent::boot();
        // 注册自定义类
        self::observe(NoticeObserver::class);
    }

    // 房东外键
    public function owner()
    {
        return $this->belongsTo(FangOwner::class,'fangowner_id');
    }
}
