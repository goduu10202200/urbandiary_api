<?php
    include 'DBConnection.php';
    
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
   
    $type = $raw_post_data['type'];


    $sql = "SELECT * FROM scheduled WHERE type = '".$type."' ";
    $result = $conn->query($sql);
    $row = $result->fetch_array();

    $data = $row['title'];
    if ($data != "") {
        echo("在".$row['location']."，".$row['title']);
    } else {
        echo "";
    }
   
    $conn->close();
