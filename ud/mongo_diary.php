<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

 // 資料表
 $collection = 'diary';
 
 // 設定變數
$today = date("Y-m-d");
$content =  $raw_post_data['content'];
$imagefilename =  $raw_post_data['imagefilename'];

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 插入資料，BulkWrite是批量插入
$bulk = new MongoDB\Driver\BulkWrite;

// 新增資料
$bulk->insert([
    'username'      => "mau123@gmail.com",
    'content'       => $content,
    'date'          => $today,
    'imagefilename' => $imagefilename,
    'jieba_check'   => '0'
]);

try {
    // 執行BulkWrite
    $manager->executeBulkWrite($dbname.'.'.$collection, $bulk);
    header('Location: http://172.20.10.3:5000/jieba');
    // header('Location: http://192.168.1.104:5000/jieba');
    
    exit;
} catch (MongoCursorException $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
