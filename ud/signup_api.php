<?php
    include 'DBConnection.php';
    
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
   
    $name =  $raw_post_data['name'];
    $username = $raw_post_data['account'];
    $password =  $raw_post_data['password'];
    $today = date("Y-m-d H:i:s");

    $sql = "INSERT INTO member (username, password,name,created_at)
    VALUES ('".$username."','".$password."','".$name."','".$today."')";

    if ($conn->query($sql) === true) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
