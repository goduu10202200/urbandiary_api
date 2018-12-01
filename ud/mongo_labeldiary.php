<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

  // 資料表
 $collection = 'scheduled';

 // 設定變數
 $response_data[] = "";
 $today = date("Y-m-d");
 $type = $raw_post_data['type'];
  

// 連線資料庫
$manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

// 查詢條件
$filter = ['type' =>  $type, 'date' => $today, 'status' =>  1];          // 欄位名及匹配條件

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
        "mood"   =>   $decode_data['mood'],
    );
}

$mood_1 = array("好累喔！", "好厭煩。");
$mood_2 = array("有點累。");
$mood_3 = array("感覺還行。", "覺得還可以。");
$mood_4 = array("感覺不錯！");
$mood_5 = array("心情愉悅(๑´ڡ`๑)");
$mood_6 = array("開心開心～", "讚喔XD");
$mood_7 = array("超開心的啦～", "超讚ohya！", "水喔～");



// 這個方法很爛不要學
if (count($response_data) > 1) {
    $decode_data = json_decode(json_encode($response_data[(count($response_data)-1)]), true);
    switch($decode_data['mood']){
        case 1:
            $mood = $mood_1[rand(0,count($mood_1)-1)];
        break;
        case 2:
            $mood = $mood_2[rand(0,count($mood_2)-1)];
        break;
        case 3:
            $mood = $mood_3[rand(0,count($mood_3)-1)];
        break;
        case 4:
            $mood = $mood_4[rand(0,count($mood_4)-1)];
        break;
        case 5:
            $mood = $mood_5[rand(0,count($mood_5)-1)];
        break;
        case 6:
            $mood = $mood_6[rand(0,count($mood_6)-1)];
        break;
        case 7:
            $mood = $mood_7[rand(0,count($mood_7)-1)];
        break;
        default:
            $mood = "";

    }
    if($decode_data['location'] === "地點"){
        echo($decode_data['title']."，".$mood);
    }
    else{
        echo("在".$decode_data['location']."，".$decode_data['title']."，".$mood);
    }
    } else {
    echo "";
}
