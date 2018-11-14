<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    $title = $raw_post_data['title'];
    // $content =  $raw_post_data['content'];
    $type =  $raw_post_data['type'];
    $location = $raw_post_data['location'];
    $latitude = $raw_post_data['latitude'];
    $longitude = $raw_post_data['longitude'];
    $date =  $raw_post_data['date'];
    $time =  $raw_post_data['time'];
    $today = date("Y-m-d H:i:s");

    $sql = "INSERT INTO scheduled (username, title, type, location, latitude, longitude, date, time, created_at)
    VALUES (1, '".$title."' ,'".$type."' ,'".$location."', $latitude, $longitude, '".$date."', '".$time."','".$today."')";

    if ($conn->query($sql) === true) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    
    //echo $location." , ".$narrative;
