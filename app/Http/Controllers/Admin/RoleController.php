<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /**
     * 列表
     */
    public function index(Request $request)
    {
        // 获取搜索框
        $name = $request->get('name');

        // 分页 搜索
        $data = Role::when($name, function($query) use ($name){
            $query->where('name','like',"%{$name}%");
        })->paginate($this->pagesize);

        return view('admin.role.index',compact('data','name'));
    }

    /**
     * 添加显示
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 添加处理
     */
    public function store(Request $request)
    {
        // 异常处理
        try{

            $this->validate($request, [
                'name' => 'required|unique:roles,name'
            ]);
        } catch(\Exception $e){
            return ['status'=>1000,'msg'=>'验证不通过'];
        }
        // 接受 all 接受所有的数据
        Role::create($request->only('name'));

        return ['status'=>0,'msg'=>'添加角色成功'];
    }

    /**
     * 根据 ID 显示对应信息
     */
    public function show($id)
    {
        //
    }

    /**
     * 修改处理
     */
    public function edit(int $id)
    {
        $model = Role::find($id);
        return view('admin.role.edit',compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // 异常处理
        try{

            $this->validate($request, [
                // unique: 表名,唯一字段,[排除行的值,以那个字段来排除]
                'name' => 'required|unique:roles,name,' . $id . ',id' // 排除 id=3 的哪行名为那么的字段值
            ]);
        } catch(\Exception $e){
            return ['status'=>1000,'msg'=>'验证不通过'];
        }

        // 修改用户入库
        Role::where('id',$id)->update($request->only('name'));

        return ['status'=> 0, 'msg' => '修改用户成功'];
    }

    // 给角色分配权限
    public function node(Role $role)
    {
//        dump($role->nodes()->pluck('name'));
//        dump($role->nodes->toArray());
        $nodeAll = (new Node)->getAllList();
        // 读取当前角色所拥有的权限
        $nodes = $role->nodes()->pluck('id')->toArray();

        return view('admin.role.node',compact('role','nodeAll','nodes'));
    }

    // 分配管理
    public function nodeSave(Request $request,Role $role)
    {
        $role->nodes()->sync($request->get('node'));
        return redirect(route('admin.role.node',$role));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
