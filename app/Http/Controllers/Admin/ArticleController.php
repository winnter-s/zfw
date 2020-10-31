<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddArtRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * 列表展示
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        if($request->header('X-Requested-With') == 'XMLHttpRequest'){ // ajax 请求
//
//            // 排序
//            ['column'=>$column,'dir'=>$dir] = $request->get('order')[0];
//            // 排序字段
//            $orderField = $request->get('columns')[$column]['data'];
//            // 开始位置
//            $start = $request->get('start',0);
//            // 开启时间
//            $datemin = $request->get('datemin');
//            // 结束时间 =
//            $datemax = $request->get('datemax');
//            // 搜索关键词
//            $title = $request->get('title');
//
//            $query = Article::where('id','>',0);
//            // 日期
//            if(!empty($datemin) && !empty($datemax)){
//                // start time
//                $datemin = date('Y-m-d H:i:s', strtotime($datemin.' 00:00:00'));
//                // end time
//                $datemax = date('Y-m-d H:i:s', strtotime($datemax.' 23:59:59'));
//
//                $query->whereBetween('created_at',[$datemin,$datemax]);
//            }
//            // 搜索关键词
//            if(!empty($title)){
//                $query->where('title','like',"%{$title}%");
//            }
//            // 获取记录数
//            $length = min(100,$request->get('length',10));
//            // 记录总数
//            $total = $query->count();
//            // 获取数据
//            //$data = $query->orderBy($orderField,$dir)->offset($start)->limit($length)->get(); // 进行搜索排序
//            $data = Article::offset($start)->limit($length)->get(); // 没有进行排序
//
//            $result = [
//                'draw' => $request->get('draw'),
//                'recordsTotal' => $total,
//                'recordsFiltered' => $total,
//                'data' => $data
//            ];
//            return json_encode($result);
//        }
//        return view('admin.article.index');
        if ($request->header('X-Requested-With') == 'XMLHttpRequest') {   // ajax请求

            // 排序
            //['column' => $column, 'dir' => $dir] = $request->get('order')[0];

            $orderArr = $request->get('order')[0];
            // 排序索引
            $column = $orderArr['column'];
            // 排序类型 升还是降
            $dir = $orderArr['dir'];
            // 排序字段
            $orderField = $request->get('columns')[$column]['data'];

            // 开启位置
            $start = $request->get('start', 0);
            // 开启时间
            $datemin = $request->get('datemin');
            // 结束时间
            $datemax = $request->get('datemax');
            // 搜索关键字
            $title = $request->get('title');

            // 查询对象
            $query = Article::where('id', '>', 0);
            // 日期
            if (!empty($datemin) && !empty($datemax)) {
                // 开始时间
                $datemin = date('Y-m-d H:i:s', strtotime($datemin . ' 00:00:00'));
                // 结束时间
                $datemax = date('Y-m-d H:i:s', strtotime($datemax . ' 23:59:59'));

                $query->whereBetween('created_at', [$datemin, $datemax]);
            }
            // 搜索关键词
            if (!empty($title)) {
                $query->where('title', 'like', "%{$title}%");
            }

            // 获取记录数
            $length = min(100, $request->get('length', 10));
            // 记录总数
            $total = $query->count();

            // 获取数据
            $data = $query->orderBy($orderField, $dir)->offset($start)->limit($length)->get();
            /*
            draw: 客户端调用服务器端次数标识
            recordsTotal: 获取数据记录总条数
            recordsFiltered: 数据过滤后的总数量
            data: 获得的具体数据
            注意：recordsTotal和recordsFiltered都设置为记录的总条数
            */
            $result = [
                'draw' => $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ];
            return $result;


        }
        // 取出所有的文章数据
        //$data = Article::all();

        return view('admin.article.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.create');
    }

    public function upfile(Request $request)
    {
        // 封面图片
        $pic = config('up.pic');
        if($request->hasFile('file')){
            // 上传
            // 参数2 配置的节点名称
            $ret = $request->file('file')->store('','article');
            $pic = '/uploads/article/' . $ret;
        }

        return ['status' => 0,'url'=>$pic];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddArtRequest $request)
    {

        $post = $request->except(['_token','file']);
        // 添加到数据库
        Article::create($post);
        return redirect(route('admin.article.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * 文章修改
     */
    public function edit(Article $article)
    {
        return view('admin.article.edit',compact('article'));
    }

    /**
     * 修改处理
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //软删除
        $article->delete();
        return ['id' => 1];
    }
}
