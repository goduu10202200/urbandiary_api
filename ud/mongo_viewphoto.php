<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

  // 資料表
 $collection = 'uploadfile';

 // 設定變數
$today = date("Y-m-d");
 
// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['created_at' =>  $today];          // 欄位名及匹配條件zz

// 查詢資料
$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);

// 判斷是否有資料
$i = 0;
foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $response_data[] = array(
        "name" =>   $decode_data['name']
    );
    $i += ($key+1);
}


if ($i > 0) {
    echo json_encode($response_data[$key]);
} else {
    echo "No data";
}
