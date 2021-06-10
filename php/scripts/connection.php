<?php

function connect() {

  $serverName = 'ec2-54-228-174-49.eu-west-1.compute.amazonaws.com:5432';
  $username = 'bcbsuxzwykzrad';
  $password = 'dd4e50fee47e6fd754895e93851a373869f5abdc2b7e9d8045104373af1b032e';
  $db = 'ddt2oo41qdds8v';

  $connection = mysqli_connect($serverName, $username, $password, $db);
  if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $connection;
}

?>