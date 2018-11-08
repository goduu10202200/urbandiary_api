<?php
    const HOST = "localhost";
    const DBNAME = "ud";
    const USERNAME = "root";
    const PASSWORD = "1234";

    date_default_timezone_set("Asia/Taipei");

    $conn = new mysqli(HOST, USERNAME, PASSWORD, DBNAME);
    $conn->set_charset("utf8");
