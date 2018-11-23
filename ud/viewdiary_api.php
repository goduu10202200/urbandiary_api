<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    $date =  $raw_post_data['date'];

    $sql = "SELECT * FROM diary WHERE date = '".$date."' ";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        while ($row_chat = $result -> fetch_assoc()) {
            $response_data[] = array(
                "id"            =>$row_chat['id'],
                "date"         =>$row_chat['date'],
                "content"	       =>$row_chat['content'],
                "location"   =>$row_chat['location'],
            );
        }
    } else {
        echo "No Results Found.";
    }

    echo json_encode($response_data);
    $conn->close();
