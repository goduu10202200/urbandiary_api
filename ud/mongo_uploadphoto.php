<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

$uploaddir = "../../assets/uploadfile/images/";
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

    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Upload failed";
}
