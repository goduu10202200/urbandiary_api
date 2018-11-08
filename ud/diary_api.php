<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    $content =  $raw_post_data['content'];
    $today = date("Y-m-d");

    $sql = "INSERT INTO diary (username, content, date)
    VALUES (1, '".$content."', '".$today."')";

    if ($conn->query($sql) === true) {
        echo "New record created successfully";
        header('Location: http://192.168.3.28:5000/jieba');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    
    //echo $location." , ".$narrative;
