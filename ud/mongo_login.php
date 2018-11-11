<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

 $collection = 'member';
 $username = $raw_post_data['account'];
 $password =  $raw_post_data['password'];
 

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['username' =>  $username,'password' => $password];          // 欄位名及匹配條件

// 查詢資料
$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);

// 判斷是否有資料
if (count($cursor->toArray()) > 0) {
    echo "successfully";
} else {
    echo "0 results";
}
