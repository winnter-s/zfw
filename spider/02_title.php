<?php
// 永不超时  不能用nginx apache 他们有超时设置 应该用 cli 命令行
set_time_limit(0);
include __DIR__ . '/function.php';
require __DIR__ . '/vendor/autoload.php';
use QL\QueryList;

$db = new PDO('mysql:host=localhost;dbname=wwwzfwcom;charset=utf8mb4','root','');
$rang = range(1,2);

foreach ($rang as $page){
    $url = 'https://news.ke.com/bj/baike/033/pg'.$page.'/';

    $html = http_request($url);
    // 分析采集到的内容
    $datalist = QueryList::Query($html,[
        'pic' => ['.lj-lazy','data-original','',function($item){
            // 得到扩展名
            $ext = pathinfo($item,PATHINFO_EXTENSION);
            // 生成文件名
            $filename = md5($item). '_' . time() . '.' . $ext;
            // 生成到本地的路径
            $filepath = dirname(__DIR__). '/public/uploads/article/'.$filename;
            file_put_contents($filepath,http_request($item));
            return '/uploads/article/'.$filename;
    }],
        'title' => ['.item .text .LOGCLICK', 'text'],
        'desn' => ['.item .text .summary', 'text'],
        'url' => ['.item .text > a', 'href']
    ])->data;
    // 入库
    foreach($datalist as $val){
//        extract($val);
        $sql = "insert into zfw_articles (title,desn,pic,url,body) values (?,?,?,?,'')";
        $stmt = $db->prepare($sql);
        // 入库
        $stmt->execute([$val['title'],$val['desn'],$val['pic'],$val['url']]);
    }
}


//var_dump($datalist);
