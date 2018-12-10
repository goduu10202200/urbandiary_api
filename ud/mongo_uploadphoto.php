<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

$uploaddir = "../../assets/uploadfile/images/";
$uploadURL = "http://140.135.114.124:90/ud/assets/uploadfile/images/";
$uploadfile = $uploaddir.basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadfile)) {
    //上傳進資料夾成功
    
    // 資料表
    $collection = 'uploadfile';
        
    // 設定變數
    $today = date("Y-m-d");
            
    // 連線資料庫
    $manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

    // 插入資料，BulkWrite是批量插入
    $bulk = new MongoDB\Driver\BulkWrite;

    // 新增資料
    $bulk->insert([
        'name'    => $_FILES["file"]["name"],
        'created_at'  => $today
    ]);

    // 執行BulkWrite
    $manager->executeBulkWrite($dbname.'.'.$collection, $bulk);


    // 這邊串上傳後取得的圖片路徑跟檔名
    $image_url = $uploadURL.$_FILES['file']['name'];
    
    $api_credentials = array(
         'key' => 'acc_1cb310f3992c397',
         'secret' => 'b0af6bd957c3159b0e67842523174a32'
     );
 
    // 參數 ['語系', '閥值']
    $lang = 'zh_cht';
    $thresold = '40';
 
    // 開始執行 curl
    $ch = curl_init();
 
    curl_setopt($ch, CURLOPT_URL, 'https://api.imagga.com/v2/tags?image_url='.$image_url.'&language='.$lang.'&threshold='.$thresold);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERPWD, $api_credentials['key'].':'.$api_credentials['secret']);
 
    $response = curl_exec($ch);
    curl_close($ch);
 
    $json_response = json_decode($response);
     
    echo json_encode($json_response);


    // 資料表
    $collection = 'imagga';

    // 連線資料庫
    $manager = new MongoDB\Driver\Manager("mongodb://".$dbhost);

    // 插入資料，BulkWrite是批量插入
    $bulk = new MongoDB\Driver\BulkWrite;

    // 新增資料
    $bulk->insert([
        'filename'      => $_FILES["file"]["name"],
        'resault'       => $json_response,
    ]);

    try {
        // 執行BulkWrite
        $manager->executeBulkWrite($dbname.'.'.$collection, $bulk);
        echo "File is valid, and was successfully uploaded.\n";
    } catch (MongoCursorException $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} else {
    echo "Upload failed";
}
