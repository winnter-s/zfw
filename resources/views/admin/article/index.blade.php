@extends('admin.common.main')
@section('cnt')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 文章管理
        <span class="c-gray en">&gt;</span> 文章列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i>
        </a>
    </nav>
    {{-- 消息提示 --}}
    @include('admin.common.msg')
    <div class="page-container">
        <form method="get" class="text-c" onsubmit="return dopost()">
            <input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}' })" id="datemin" class="input-text Wdate" style="width:120px;">
            -
            <input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d' })" id="datemax" class="input-text Wdate" style="width:120px;">
            文章标题：
            <input type="text" class="input-text" style="width:250px" placeholder="文章标题" value="{{ request()->get('title') }}" id="title" name="title" autocomplete="off">
            <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜文章</button>
        </form>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="{{route('admin.article.create')}}" class="btn btn-primary radius">
                <i class="Hui-iconfont">&#xe600;</i> 添加文章</a>
        </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="100">文章标题</th>
                    <th width="100">加入时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
            </table>

        </div>
    </div>
@endsection
@section('js')
    <script>
        var dataTable = $('.table-sort').DataTable({
            // 下拉的分页数量
            lengthMenu:[5,10,15,20,50],
            // 隐藏搜索
            searching:false,
            columnDefs:[
                {targets:[3],orderable:false}
            ],
            // 开启服务端分页
            serverSide:true,
            // 进行 ajax 请求
            ajax: {
                // 请求地址
                url:'{{ route('admin.article.index') }}',
                // 请求方式
                type:'get',
                // 参数 动态获取表单数据用 function
                data: function (ret){
                    ret.datemin = $('#datemin').val();
                    ret.datemax = $('#datemax').val();
                    ret.title = $.trim($('#title').val());
                }
            },
            // 指定每一列显示的数据
            columns:[
                {data:'id',className:'text-c'},
                {data:'title'},
                {data:'created_at'},
                {data:'aaa',defaultContent:'默认值'},
            ],
            // 回调方法
            /**
             *
             * @param row 当前行的dom
             * @param data 当前行数据
             * @param dataIndex 当前行索引
             */
            createdRow:function (row,data,dataIndex){
                // 行的最后一列
                var td = $(row).find('td:last-child');
                // 当前 id 号
                var id = data.id;
                // 显示的html内容
                var html = `
                    <a href="/admin/article/${id}/edit" class="label label-secondary radius">修改</a>
                    <a href="/admin/article/${id}" class="label label-warning radius">删除</a>
                `;
                // html 添加到 td 中
                td.html(html);
            }
        });

        // 表单提交
        function dopost(){
            dataTable.ajax.reload();
            // 取消表单默认行为
            return false;
        }
    </script>
@endsection
