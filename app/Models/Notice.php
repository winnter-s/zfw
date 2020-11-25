<?php

namespace App\Models;

class Notice extends Base
{
    // 房东外键
    public function owner()
    {
        return $this->belongsTo(FangOwner::class,'fangowner_id');
    }
}
