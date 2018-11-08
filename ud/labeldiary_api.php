<?php
    include 'DBConnection.php';
    
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
   
    $type = $raw_post_data['type'];


    $sql = "SELECT * FROM scheduled WHERE type = '".$type."' ";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    // if ($result->num_rows >0) {
       
    // } else {
    //     echo "No Results Found.";
    // }
    $data = $row['title'];
    if ($data != "") {
        echo("在".$row['location']."，".$row['title']);
    } else {
        echo "";
    }
   
    $conn->close();
    //{"id":"1","date":"2018-10-24","time":"14:00","location":"操場","content":"跑步", "status":"0"}
