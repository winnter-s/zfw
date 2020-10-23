
@extends('admin.common.main')
@section('cnt')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 用户中心
        <span class="c-gray en">&gt;</span> 权限列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i>
        </a>
    </nav>
    {{-- 消息提示 --}}
    @include('admin.common.msg')
    <div class="page-container">
        <form method="get" class="text-c"> 输入想要搜索的角色名：
            <input type="text" class="input-text" style="width:250px" placeholder="角色" value="{{ $name }}" id="" name="name" autocomplete="off">
            <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜角色</button>
        </form>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="{{route('admin.role.create')}}" class="btn btn-primary radius">
                <i class="Hui-iconfont">&#xe600;</i> 添加角色</a>
        </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="100">角色名称</th>
                    <th width="100">查看权限</th>
                    <th width="130">加入时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr class="text-c">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <a href="{{ route('admin.role.node',$item) }}" class="label label-success radius">权限</a>
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td class="td-manage">
                            <a href="{{ route('admin.role.edit',$item) }}" class="label label-secondary radius">修改</a>
                            <a href="{{route('admin.role.destroy',['id' => $item->id])}}" class="label label-warning radius">恢复</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{-- 分页 --}}
            {{$data->links()}}
        </div>
    </div>
@endsection
@section('js')
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/admin/lib/laypage/1.2/laypage.js"></script>
<script>
    const _token = "{{ csrf_token() }}";
    // 给删除按钮绑定事件
    $('.delbtn').click(function (evt) {
        let url = $(this).attr('href');
        $.ajax({
            url,
            data:{_token},
            type:'DELETE',
            datatype:'json'
        }).then(({status,msg}) =>{
            if ( status == 0){
                // 提示插件
                layer.msg(msg,{time:2000, icon:1},()=>{
                    // 删除当前行
                    $(this).parents('tr').remove();
                });
            }
        });
        // jquery 取消默认事件
        return false;
    });

    // 全选删除
    function deleteAll(){
        // 询问框
        layer.confirm('确认删除选中用户吗?',{
            btn: ['确认删除','思考一下']
        },()=>{
            // 选中用户
            let ids = $('input[name="id[]"]:checked');
            // 删除的 ID
            let id = [];
            // 循环
            $.each(ids, (key,val) =>{
                // 把 dom 对象 转为 jquery 对象 $(dom对象)
                id.push($(val).val());
            });

            if(id.length > 0){
                // 发起ajax
                $.ajax({
                    url:"{{ route('admin.user.delall') }}",
                    data:{id,_token},
                    type:'DELETE'
                }).then(ret=>{
                    if(ret.status == 0){
                        layer.msg(ret.msg,{time:2000,icon:1},()=>{
                            location.reload()
                        })
                    }
                })
            }
        });
    }
</script>
@endsection
