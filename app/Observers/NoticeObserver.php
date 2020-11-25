<?php

namespace App\Observers;

use App\Jobs\NoticeJob;
use App\Models\Notice;

class NoticeObserver
{
    /**
     * 添加完成后, 执行方法 create
     */
    public function created(Notice $notice)
    {
         // 发布任务
        dispatch(new NoticeJob());
    }

    /**
     * 修改完成后, 执行方法 update
     */
    public function updated(Notice $notice)
    {
        //
    }

}
