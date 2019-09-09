<?php
    session_start();
    $website_name = "Online examination portal";

    $host = "localhost";
    $username = "root";
    $password = "majd161997";
    $database = "examination";

    $connect = mysqli_connect($host, $username, $password, $database);

    if (mysqli_connect_errno())
    {
        die ("Failed to connect to database: ".mysqli_connect_error());
    }

    date_default_timezone_set("Europe/Paris");

?>
