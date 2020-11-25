<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        // 关联取出数据
        $data = Notice::with(['owner'])->paginate();
        return view('admin.notice.index',compact('data'));
    }

    /**
     * 添加显示
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * 添加入库
     */
    public function store(Request $request)
    {
        // 入库操作
        $postData = $request->except(['_token']);
        Notice::create($postData);
        // 此处不需要任何添加完成后的代码, 我们在时间观察类中写

        // 跳转
        return redirect(route('admin.notice.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        //
    }
}
