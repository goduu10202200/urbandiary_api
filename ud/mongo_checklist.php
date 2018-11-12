<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

 $collection = 'scheduled';
 $username =  $raw_post_data['username'];
 $status = $raw_post_data['status'];
 $title = $raw_post_data['title'];

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 插入資料，BulkWrite是批量插入
$bulk = new MongoDB\Driver\BulkWrite;

//更新資料
$bulk->update(
    ['username' => $username,'title' => $title],
    ['$set' => ['status' => $status]]
);

// 執行BulkWrite
$manager->executeBulkWrite($dbname.'.'.$collection, $bulk);
//判斷是否執行成功
// if ($conn->query($sql) === true) {
//     echo "Check successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }
