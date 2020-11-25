<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FangRequest;
use App\Models\City;
use App\Models\Fang;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FangController extends Controller
{
    /**
     * 房源列表
     */
    public function index()
    {
        // 获取数据
        // with 关联关系的调用
        $data = Fang::with(['owner'])->paginate();
        // 指定试图模板并赋值
        return view('admin.fang.index',compact('data'));
    }

    /**
     * 添加显示模板
     */
    // 依赖注入方式
    public function create(Fang $fang)
    {
        // 取关联表数据
        //$data = (new Fang())->relationData();
        $data = $fang->relationData();

        return view('admin.fang.create',$data);
    }

    // 获取城市
    public function city(Request $request)
    {
        $data = City::where('pid',$request->get('id'))->get(['id','name']);
        return $data;
    }

    /**
     * 添加处理
     */
    public function store(FangRequest $request)
    {
        // 表单数据
        $dopost = $request->except(['_token','file']);
        // 入库
        $model = Fang::create($dopost);
        // 添加数据入库成功
        // 发起 http 请求
        // 申请一个请求类, 并指定请求的过期时间
        $client = new Client(['timeout' => 5]);
        // 得到请求地址
        $url = config('gaodemap.geocode');
        $url = sprintf($url,$model->fang_addr,$model->fang_province);
        // 发起请求
        $response = $client->get($url);
        $body = (string) $response->getBody();
        $arr = json_decode($body,true);
        if(count($arr['geocodes'])>0){
            $locationArr = explode(',',$arr['geocodes'][0]['location']);
            $model->update([
                'longitude' => $locationArr[0],
                'latitude' => $locationArr[1]
            ]);
        }

        // es 数据的添加
        // 得到 es 客户端对象
        $client = ClientBuilder::create()->setHosts(config('es.host'))->build();
        // 写文档
        $params = [
            'index' => 'fang',
            'type' => '_doc',
            'id' => $model->id,
            'body' => [
                'fang_name' => $model->fang_name,
                'fang_desn' => $model->fang_desn,
            ],
        ];
        // 添加数据到索引文档中
        $response = $client->index($params);

        // 跳转
        return redirect(route('admin.fang.index'));
    }

    /**
     *
     */
    public function show(Fang $fang)
    {
        //
    }

    /**
     *  房源修改显示
     */
    public function edit(Fang $fang)
    {
        // 得到关联数据
        $data = (new Fang())->relationData();
        // 得到当前用户所属省对应列表
        $currentCityData = City::where('pid',$fang->fang_province)->get();
        // 得到当前用户所属市对应的列表
        $currentRegionData = City::where('pid',$fang->fang_city)->get();

        $data['currentCityData'] = $currentCityData;
        $data['currentRegionData'] = $currentRegionData;
        $data['fang'] = $fang;

        return view('admin.fang.edit',$data);
    }

    /**
     *
     */
    public function update(FangRequest $request, Fang $fang)
    {
        // 接受表单数据
        $putData = $request->except(['_method','_token','file']);
        // 修改入库
        $fang->update($putData);

        // 修改经纬度
        // 发起 http 请求
        // 申请一个请求类, 并指定请求的过期时间
        $client = new Client(['timeout' => 5]);
        // 得到请求地址
        $url = config('gaodemap.geocode');
        $url = sprintf($url,$fang->fang_addr,$fang->fang_province);
        // 发起请求
        $response = $client->get($url);
        $body = (string) $response->getBody();
        $arr = json_decode($body,true);
        if(count($arr['geocodes'])>0){
            $locationArr = explode(',',$arr['geocodes'][0]['location']);
            $fang->update([
                'longitude' => $locationArr[0],
                'latitude' => $locationArr[1]
            ]);
        }

        // es 数据的修改
        // 得到 es 客户端对象
        $client = ClientBuilder::create()->setHosts(config('es.host'))->build();
        // 修改文档
        $params = [
            'index' => 'fang',
            'type' => '_doc',
            // 只需要 ID 存在 就能修改, 没有就添加
            'id' => $fang->id,
            'body' => [
                'fang_name' => $fang->fang_name,
                'fang_desn' => $fang->fang_desn,
            ],
        ];
        // 修改数据到索引文档中
        $response = $client->index($params);

        return redirect(route('admin.fang.index'));
    }

    /**
     *
     */
    public function destroy(Fang $fang)
    {
        //
    }

    // 生成房源信息es索引
    public function esinit()
    {
        // 得到 es 客户端对象
        $client = ClientBuilder::create()->setHosts(config('es.host'))->build();

        // 创建索引
        $params = [
            // 生成索引的名称
            'index' => 'fang',
            // 类型 body
            'body' => [
                'settings' => [
                    // 分区数
                    'number_of_shards' => 5,
                    // 副本数
                    'number_of_replicas' => 1,
                ],
                'mappings' => [
                    '_doc' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        // 字段 类似表字段 设置类型
                        'properties' => [
                            'fang_name' => [
                                // 相当于数据查询是的 = 张三你好, 必须找到张三你好
                                'type' => 'keyword'
                            ],
                            'fang_desn' => [
                                'type' => 'text',
                                // 中文分词 张三你好 张三 你好 张三你好
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        // 创建索引
        $response = $client->indices()->create($params);
        dump($response);
    }

    // 改变房源状态
    public function changestatus(Request $request)
    {
        // 房源 ID 号
        $id = $request->get('id');
        // 房源状态
        $status = $request->get('status');
        // 根据 ID 修改状态
        Fang::where('id',$id)->update(['fang_status'=>$status]);
        return ['status'=>0,'msg'=>'修改状态成功'];
    }
}
