<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    $today =  $raw_post_data['today'];

    $sql = "SELECT * FROM scheduled WHERE date = '".$today."' ORDER BY kind ";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        while ($row_chat = $result -> fetch_assoc()) {
            $response_data[] = array(
                "id"            =>$row_chat['id'],
                "date"         =>$row_chat['date'],
                "time"	       =>$row_chat['time'],
                "location"   =>$row_chat['location'],
                "title"          =>$row_chat['title'],
                "status"       => $row_chat['status'] ,
                "kind"         => $row_chat['kind'] ,
            );
        }
    } else {
        echo "No Results Found.";
    }

    echo json_encode($response_data);
    $conn->close();
