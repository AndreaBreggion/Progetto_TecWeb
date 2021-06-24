
<?php

function connect() {

    $serverName = 'localhost:3306';
    $username = 'acanel';
    $password = 'aT1eis3aay1ohc2i';
    $db = 'acanel';

    $connection = mysqli_connect($serverName, $username, $password, $db);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $connection;
}

?>