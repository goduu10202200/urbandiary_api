<?php
 include 'DBConnection_mongo.php';

 $raw_post_data = json_decode(file_get_contents('php://input'), true);

// 資料表
 $collection = 'diary';

// 設定變數
$date =  $raw_post_data['date'];


// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['date' =>  $date];          // 欄位名及匹配條件

// 查詢資料
$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);

// 判斷是否有資料
foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $response_data[] = array(
        // "id"            =>$decode_data['id'],
        "date"         =>$decode_data['date'],
        "content"	 =>$decode_data['content'],
    );
}
echo json_encode($response_data);
