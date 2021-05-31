<?php

function connect() {

  $info = file('../database/db.txt');
  $serverName = $info[0];
  $username = $info[1];
  $password = $info[2];
  $db = $info[3];

  $connection = mysqli_connect($serverName, $username, $password, $db);
  if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $connection;
}

?>