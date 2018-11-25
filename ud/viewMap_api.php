<?php
    include 'DBConnection.php';

    $sql = "SELECT * FROM scheduled WHERE status = 1 ";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        while ($row_chat = $result -> fetch_assoc()) {
            $response_data[] = array(
                "id"            =>$row_chat['id'],
                "title"            =>$row_chat['title'],
                "date"         =>$row_chat['date'],
                'coordinates' => array(
                    'latitude'    =>$row_chat['latitude'],
                    'longitude'   =>$row_chat['longitude'],
                ),
            );
        }
    } else {
        echo "No Results Found.";
    }

    echo json_encode($response_data, JSON_NUMERIC_CHECK);
    $conn->close();
