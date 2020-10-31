<?php

// 永不超时  不能用nginx apache 他们有超时设置 应该用 cli 命令行
set_time_limit(0);
include __DIR__ . '/function.php';
require __DIR__ . '/vendor/autoload.php';

use QL\QueryList;

$db = new PDO('mysql:host=localhost;dbname=wwwzfwcom;charset=utf8mb4', 'root', '');

// 内容页面
$sql = "select id,url from zfw_articles where body=''";
$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach($rows as $item){
    $id = $item['id'];
    $url = $item['url'];

    $html = http_request($url);
    // 分析采集到的内容
    $ret = QueryList::Query($html, [
       "body" => ['.bd' , 'html']
    ])->data;
    // 内容
    $body = $ret[0]['body'];

    $sql = "update zfw_articles set body=? where id=?";
    $stmt = $db->prepare($sql);
    // 入库
    $stmt->execute([$body,$id]);

}

// 入库




//var_dump($datalist);
