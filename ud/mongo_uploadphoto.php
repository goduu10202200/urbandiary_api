<?php
 include 'DBConnection_mongo.php';
 $raw_post_data = json_decode(file_get_contents('php://input'), true);

// echo "檔案名稱: " . $_FILES["file"]["name"];

move_uploaded_file($_FILES["file"]["tmp_name"],"../../view/ud/assets/uploadfile/images/".$_FILES["file"]["name"]);
    
    
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

    
    
    
    
    // try{
    //     echo "檔案名稱: " . $_FILES["file"]["name"];
    //     // echo "檔案類型: " . $_FILES["file"]["type"];
    //     // echo "檔案大小: " . ($_FILES["file"]["size"] / 1024);
    //     // echo "暫存名稱: " . $_FILES["file"]["tmp_name"];
    //     　
    //     // if (file_exists("upload/" . $_FILES["file"]["name"])){
    //     //     echo "檔案已經存在，請勿重覆上傳相同檔案";
    //     // }else{
    //     //     move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["save_uri"]."images/".$_FILES["file"]["name"]);
    //     // }
    //     // move_uploaded_file($_FILES["file"]["tmp_name"],"http://172.20.10.2/ud/view/ud/assets/uploadfile/images/".$_FILES["file"]["name"]);
    // } 
    // catch (Exception $e) {
    //     echo 'Caught exception: ',  $e->getMessage(), "\n";
    // }

    // // $currentDir = getcwd();
    // $uploadDirectory = "localhost/ud/view/ud/assets/uploadfile/images/";

    // $errors = []; // Store all foreseen and unforseen errors here

    // $fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

    // $fileName = $_FILES['myfile']['name'];
    // $fileSize = $_FILES['myfile']['size'];
    // $fileTmpName  = $_FILES['myfile']['tmp_name'];
    // $fileType = $_FILES['myfile']['type'];
    // $fileExtension = strtolower(end(explode('.',$fileName)));

    // $uploadPath = $uploadDirectory . basename($fileName); 


    //     if (! in_array($fileExtension,$fileExtensions)) {
    //         $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
    //     }

    //     if ($fileSize > 2000000) {
    //         $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
    //     }

    //     if (empty($errors)) {
    //         $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

    //         if ($didUpload) {
    //             echo "The file " . basename($fileName) . " has been uploaded";
    //         } else {
    //             echo "An error occurred somewhere. Try again or contact the admin";
    //         }
    //     } else {
    //         foreach ($errors as $error) {
    //             echo $error . "These are the errors" . "\n";
    //         }
        
    // }
    
    // include 'DBConnection.php';
    // $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    // $content =  $raw_post_data['content'];
    // $today = date("Y-m-d");
    // $name = $obj['name'];
    // $email = $obj['email'];
    // $phone_number = $obj['phone_number'];

    // $sql = "INSERT INTO diary (username, content, date)
    // VALUES (1, '".$content."', '".$today."')";

    // if ($conn->query($sql) === true) {
    //     echo "New record created successfully";
    //     header('Location: http://192.168.3.28:5000/jieba');
    //     exit;
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }
    // $conn->close();
    
    //echo $location." , ".$narrative;
