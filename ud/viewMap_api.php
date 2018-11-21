<?php
    include 'DBConnection.php';
    $raw_post_data = json_decode(file_get_contents('php://input'), true);
    
    // $today =  $raw_post_data['today'];

    $sql = "SELECT * FROM scheduled WHERE status = 1 ";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        while ($row_chat = $result -> fetch_assoc()) {
            // $coordinates['body'] = array(
            //     'latitude'    =>$row_chat['latitude'],
            //     'longitude'   =>$row_chat['longitude'],
            // );
            $response_data[] = array(
                "key"            =>$row_chat['id'],
                "title"            =>$row_chat['title'],
                // "date"         =>$row_chat['date'],
                // "time"	       =>$row_chat['time'],
                // "coordinates"   =>$coordinates,
                'coordinates' => array(
                    'latitude'    =>$row_chat['latitude'],
                    'longitude'   =>$row_chat['longitude'],
                ),
                // "content"     =>$row_chat['title'],
                // "status"        => $row_chat['status'] ,
                // "kind"        => $row_chat['kind'] ,
            );
        }
    } else {
        echo "No Results Found.";
    }

    echo json_encode($response_data, JSON_NUMERIC_CHECK);
    $conn->close();

