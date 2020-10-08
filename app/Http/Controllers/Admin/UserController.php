<?php
// 用户后台管理
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Mail\Message;
use Mail;
// 明文密码和加密密码是否一致
use Hash;
class UserController extends BaseController
{
    //用户列表
    public function index()
    {
        // withTrashed 显示所有数据 包括软删除数据
        $data = User::orderBy('id','asc')->withTrashed()->paginate($this->pagesize);
        // 分页
        return view('admin.user.index',compact('data'));
    }

    // 添加显示
    public function create()
    {
        return view('admin.user.create');
    }
    // 添加处理
    public function store(Request $request)
    {
        $this->validate($request,[
            'username' => 'required|unique:users,username',
            'truename' => 'required',
            'password' => 'required|confirmed',
            'phone' => 'nullable|phone'
            // => 'phone' 是 自定义验证规则 「使用扩展」 中有详情
            // phone.phone 的 messages 是在替换中文验证文件中的信息
            // 第一个 phone 是在 attributes 中
            // 第二个 phone 是在 return 中
        ]);

        // 添加用户入库
        $post = $request->except(['_token','password_confirmation']);
        $userModel = User::create($post);

        // 发送邮件给用户
        Mail::send('mail.useradd',compact('userModel'),function (Message $message) use ($userModel){
            // 发给谁
            $message->to($userModel->email);
            $message->subject('开通账号邮件通知');
        });
        // 跳转到列表页
        return redirect(route('admin.user.index'))->with('success','添加用户成功');
    }

    // 删除用户操作
    public function del(int $id)
    {
        User::find($id)->delete();
        return ['status'=>0,'msg'=>'删除成功'];
    }

    // 恢复
    public function restore(int $id)
    {
        User::onlyTrashed()->where('id',$id)->restore();
        return redirect(route('admin.user.index'))->with('success','恢复用户成功');
    }

    // 全选删除
    public function delall(Request $request)
    {
        $ids = $request->get('id');
        User::destroy($ids);

        return ['status'=>0,'msg'=>'删除成功'];
    }

    // 修改显示
    public function edit(int $id)
    {
        $model = User::find($id);

        return view('admin.user.edit',compact('model'));
    }

    // 修改处理
    public function update(Request $request, int $id)
    {
        $model = User::find($id);

        // 原密码 明文
        $spass = $request->get('spassword');
        $oldpass = $model->password;
        Hash::check($spass,$oldpass);
    }
}
