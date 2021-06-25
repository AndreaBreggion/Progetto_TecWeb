<?php

function connect() {

    $serverName = 'localhost';
    $username = 'asalmaso';
    $password = 'theiGheiw7ahj0Ie';
    $db = 'asalmaso';

    $connection = mysqli_connect($serverName, $username, $password, $db);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $connection;
}

?>