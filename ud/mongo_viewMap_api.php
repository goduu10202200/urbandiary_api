<?php
 include 'DBConnection_mongo.php';

  // 資料表
 $collection = 'scheduled';

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['status' =>  1];          // 欄位名及匹配條件

// 查詢資料
$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);

// 判斷是否有資料
foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $response_data[] = array(
        "id"              =>   $decode_data['_id'],
        "title"           =>   $decode_data['title'],
        "date"           =>   $decode_data['date'],
        'coordinates' => array(
            'latitude'    =>$decode_data['latitude'],
            'longitude'   =>$decode_data['longitude'],
        ),
    );
}
echo json_encode($response_data, JSON_NUMERIC_CHECK);
