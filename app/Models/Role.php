<?php

namespace App\Models;

class Role extends Base
{
    // 角色与权限 多对多
    public function nodes()
    {
        return $this->belongsToMany(Node::class,'role_node','role_id','node_id');
    }
}
