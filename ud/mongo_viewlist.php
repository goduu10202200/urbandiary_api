<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

  // 資料表
 $collection = 'scheduled';

 // 設定變數
 $today =  $raw_post_data['today'];
 

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['date' =>  $today];          // 欄位名及匹配條件
$options = [
    'sort'       => ['kind'=>-1],
];
// 查詢資料
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);


// 判斷是否有資料
$i = 0;
foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $response_data[] = array(
        "id"              =>   $decode_data['_id'],
        // "username"  =>   $decode_data['username'],
        "date"           =>   $decode_data['date'],
        "time"	         =>   $decode_data['time'],
        "location"     =>   $decode_data['location'],
        "title"            =>   $decode_data['title'],
        "status"         =>   $decode_data['status'] ,
        "kind"           =>   $decode_data['kind'] ,
        "created_at"           =>   $decode_data['created_at'] ,
    );
    $i += ($key+1);
}


if( $i > 0 ){
    echo json_encode($response_data);
}
else{
    echo "No data";
}
