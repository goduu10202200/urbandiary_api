<?php
    include 'DBConnection.php';

    $raw_post_data = json_decode(file_get_contents('php://input'), true);
   
    $username = $raw_post_data['account'];
    $password =  $raw_post_data['password'];

    $sql = "SELECT username,password  FROM member WHERE username = '".$username."'  AND password='".$password."' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "successfully";
    } else {
        echo "0 results";
    }
    $conn->close();
