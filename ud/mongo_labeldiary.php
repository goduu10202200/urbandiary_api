<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

  // 資料表
 $collection = 'scheduled';

 // 設定變數
 $response_data[] = "";
 $type = $raw_post_data['type'];
  

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['type' =>  $type];          // 欄位名及匹配條件

// 查詢資料
$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);
// 判斷是否有資料

foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $title = $decode_data['title'];
    
    $response_data[] = array(
        "title"         =>   $decode_data['title'],
        "location"   =>   $decode_data['location'],
    );
}

// 這個方法很爛不要學
if (count($response_data) > 1) {
    $decode_data = json_decode(json_encode($response_data[(count($response_data)-1)]), true);
    echo("在".$decode_data['location']."，".$decode_data['title']);
} else {
    echo "";
}
