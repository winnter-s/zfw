@extends('admin.common.main');
@section('cnt')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 用户中心
        <span class="c-gray en">&gt;</span> 添加角色
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i>
        </a>
    </nav>
    <article class="page-container">
        <form action="{{route('admin.role.store')}}" method="post" class="form form-horizontal" id="form-member-add">
            @csrf
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" name="name" autocomplete="off">
                </div>
            </div>

            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;添加角色&nbsp;&nbsp;">
                </div>
            </div>
        </form>
    </article>
@endsection

@section('js')
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>

    <script>

        // 前端表单验证
        $("#form-member-add").validate({
            rules:{
                // 表单元素名称
                name:{
                    required:true
                }
            },
            // 取消键盘事件
            onkeyup:false,
            // 验证成功后的样式
            success:"valid",
            // 验证通过后, 处理的方法
            submitHandler:function(form){
                // 表单提交
                let url = $(form).attr('action');
                // 表单序列化  _token=aaaa&name=dfsfe
                let data = $(form).serialize();

                // jquery post提交
                $.post(url, data).then(({status,msg})=>{
                    if(status==0){
                        layer.msg(msg,{icon:1,time:2000},()=>{
                            location.href = "{{ route('admin.role.index') }}";
                        })
                    } else {
                        layer.msg(msg,{icon:2,time:2000});
                    }
                })
            }
        })
    </script>
@endsection
