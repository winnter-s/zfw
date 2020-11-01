<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fangattr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FangAttrController extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        // 实例化模型
        $model = new Fangattr();
        // 取数据
        $data = $model->getList();

        // 指定驶入并赋值
        return view('admin.fangattr.index',compact('data'));
    }

    /**
     * 添加界面显示
     */
    public function create()
    {
        // 获取顶部属性
        $data = Fangattr::where('pid',0)->get();
        // 指定试图并赋值
        return view('admin.fangattr.create',compact('data'));
    }
    // 文件上传
    public function upfile(Request $request)
    {
        // 默认图标
        $pic = config('up.pic');
        if($request->hasFile('file')){
            // 上传
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store('','fangattr');
            $pic = '/uploads/fangattr/' . $ret;
        }

        return ['status' => 0,'url'=>$pic];
    }

    /**
     * 添加处理
     */
    public function store(Request $request)
    {
        //表单验证
        $this->validate($request,[
            'name' => 'required'
        ]);

        // 验证通过后, 入库并跳转到列表页面
        // 获取数据
        $postData = $request->except(['_token','file']);
        $postData['field_name'] = !empty($postData['field_name']) ? $postData['field_name'] : '';
        // 入库
        Fangattr::create($postData);
        // 跳转
        return redirect(route('admin.fangattr.index'));
    }


    /**
     * 修改页面显示
     */
    public function edit(Fangattr $fangattr)
    {
        // 获取顶部属性
        $data = Fangattr::where('pid',0)->get();
        return view('admin.fangattr.edit',compact('data','fangattr'));
    }

    /**
     * 修改处理
     */
    public function update(Request $request, Fangattr $fangattr)
    {
        //表单验证
        $this->validate($request,[
            'name' => 'required'
        ]);

        // 验证通过后, 入库并跳转到列表页面
        // 获取数据
        $postData = $request->except(['_token','file','_method']);
        $postData['field_name'] = !empty($postData['field_name']) ? $postData['field_name'] : '';
        // 修改入库
        $fangattr->update($postData);
        // 跳转
        return redirect(route('admin.fangattr.index'));
    }

    /**
     * 删除操作
     */
    public function destroy(Fangattr $fangattr)
    {
//        $fangattr->delete();
        return ['status'=>0,'msg'=>'删除成功'];
    }
}
