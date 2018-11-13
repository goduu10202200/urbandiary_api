<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

 // 資料表
 $collection = 'scheduled';
 
 // 設定變數
 $kind = 'list';
 $today = date("Y-m-d H:i:s");
 $title = $raw_post_data['title'];
 $type =  $raw_post_data['type'];
 $date =  $raw_post_data['date'];
 $time =  $raw_post_data['time'];
 $location = $raw_post_data['location'];
 

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 插入資料，BulkWrite是批量插入
$bulk = new MongoDB\Driver\BulkWrite;

// 新增資料
$bulk->insert([
    'username'      => 1,
    'title'               => $title,
    'content'          => '',
    'type'               => $type,
    'location'         => $location,
    'date'               => $date,
    'time'               => $time,
    'status'             => 0,
    'kind'               => $kind,
    'created_at'     =>  $today
]);

// 執行BulkWrite
$manager->executeBulkWrite($dbname.'.'.$collection, $bulk);
