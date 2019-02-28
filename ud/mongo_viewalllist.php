<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

  // 資料表
 $collection = 'scheduled';

 // 設定變數
//  $today =  $raw_post_data['today'];
 

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['kind' =>  'list'];          // 欄位名及匹配條件
$options = [
    'sort'       => ['time'=>1],
];
// 查詢資料
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery($dbname.'.'.$collection, $query);


// 判斷是否有資料
$i = 0;
foreach ($cursor as $key =>  $document) {
    $decode_data = json_decode(json_encode($document), true);
    $response_data[] = array(
        "date"    =>   $decode_data['date'],
        "title"   =>   $decode_data['title'],
        "time"	         =>   $decode_data['time'],
        "location"       =>   $decode_data['location'],
        "status"         =>   $decode_data['status'] ,
        "created_at"     =>   $decode_data['created_at'] 
        // $decode_data['date'] => array(
        //     "title"          =>   $decode_data['title'],
        //     // "kind"           =>   $decode_data['kind'] 
        // ),
    );
    $i += ($key+1);
}


if( $i > 0 ){
    echo json_encode($response_data);
}
else{
    echo "No data";
}
