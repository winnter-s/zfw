<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FangOwnerExport;
use App\Models\FangOwner;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FangOwnerController extends BaseController
{
    /**
     * 房东列表
     */
    public function index()
    {
        // 获取用户数据
        $data = FangOwner::paginate($this->pagesize);
        // 赋值给试图模板
        return view('admin.fangowner.index',compact('data'));
    }

    /**
     * 添加显示
     */
    public function create()
    {
        return view('admin.fangowner.create');
    }

    // 文件上传
    public function upfile(Request $request)
    {
        // 默认图标
        $pic = config('up.pic');
        if($request->hasFile('file')){
            // 上传
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store('','fangowner');
            $pic = '/uploads/fangowner/' . $ret;
        }

        return ['status' => 0,'url'=>$pic];
    }
    /**
     * 添加房东处理
     */
    public function store(Request $request)
    {
        // 表单验证
        $this->validate($request,[
            'name' => 'required',
            'phone' => 'required'
        ]);

        // 获取数据
        $postData = $request->except(['_token','file']);
        // 去除#
        $postData['pic'] = trim($postData['pic'],'#');

        // 入库
        FangOwner::create($postData);
        // 跳转到列表页面
        return redirect(route('admin.fangowner.index'));
    }

    /**
     * 显示图片
     */
    public function show(FangOwner $fangowner)
    {
        $piclist = explode('#',$fangowner->pic);

        $html = '';
        // 便利数组
        array_map(function ($item) {
            echo "<div> <img src=$item style='width: 150px;' /> </div>";
        },$piclist);

        return '';
    }

    // 导出
    public function exports()
    {
        return Excel::download(new FangOwnerExport(),'fangdong.xlsx');
    }

    /**
     *
     */
    public function edit(FangOwner $fangowner)
    {
        //
    }

    /**
     *
     */
    public function update(Request $request, FangOwner $fangowner)
    {
        //
    }

    /**
     *
     */
    public function destroy(FangOwner $fangowner)
    {
        //
    }

    // 文件删除
    public function delfile(Request $request)
    {
        $filepath = $request->get('file');
        // 得到真实的地址
        $path = public_path().$filepath;
        // 删除指定的文件
        unlink($path);
        return ['status' => 0, 'msg' => '成功'];
    }
}
