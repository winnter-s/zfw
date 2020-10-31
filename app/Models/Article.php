<?php

namespace App\Models;

class Article extends Base
{
    // 追加一个字段
    protected $appends = ['action'];

    // 访问器 结合追加字段
    public function getActionAttribute()
    {
        return $this->editBtn('admin.article.edit').' ' .$this->deleteBtn('admin.article.destroy');
    }
}
