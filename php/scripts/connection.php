<?php

function connect() {

  $serverName = 'localhost:3306';
  $username = 'root';
  $password = 'Dalaskogen1444';
  $db = 'tecweb';

  $connection = mysqli_connect(parse_url(getenv('DATABASE_URL')));
  if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $connection;
}

?>