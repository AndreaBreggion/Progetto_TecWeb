<?php
session_start();
  require_once('connection.php');
  $connection = connect();
  $query = 'SELECT uId as id FROM presepi WHERE id = '.$_SESSION['lastVisitedPresepe'];
  $result = $connection->query($query);
  $row = mysqli_fetch_assoc($result);
  if($_SESSION['uId'] == $row['id'] || $_SESSION['loggedin'] == 'admin') {
      $query = 'DELETE FROM presepi WHERE id = '.$_SESSION['lastVisitedPresepe'];
      $connection->query($query);
      header("location: ../presepiInGara.php");
  }
?>