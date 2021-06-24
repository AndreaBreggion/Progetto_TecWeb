<?php

function connect() {

  $serverName = 'localhost:3306';
  $username = 'root';
  $password = '';
  $db = 'asalmaso';

  $connection = mysqli_connect($serverName, $username, $password, $db);
  if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $connection;
}

?>
