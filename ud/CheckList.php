<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    $id =  $raw_post_data['id'];
    $status = $raw_post_data['status'];

    $sql = "UPDATE scheduled SET status = $status WHERE id = $id;";

    if ($conn->query($sql) === true) {
        echo "Check successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    
    //echo $location." , ".$narrative;
