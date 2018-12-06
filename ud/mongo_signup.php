<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

 // 資料表
 $collection = 'member';
 
 // 設定變數
 $today = date("Y-m-d H:i:s");
 $name =  $raw_post_data['name'];
 $username = $raw_post_data['account'];
 $password =  $raw_post_data['password'];
 

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 插入資料，BulkWrite是批量插入
$bulk = new MongoDB\Driver\BulkWrite;

// 新增資料
$bulk->insert([
    'username' => $username,
    'name' =>$name,
    'password' => $password,
    'created_at' => $today,
]);

// 執行BulkWrite
$manager->executeBulkWrite($dbname.'.'.$collection, $bulk);
