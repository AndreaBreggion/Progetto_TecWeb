<?php
  require_once('hasUserLiked.php');
  require_once('connection.php');
  session_start();
  if(!isset($_SESSION['uId'])) {
    header('location: ../login.php');
    exit;
  } else {
      $connection = connect();
      if(hasUserLiked($connection, $_SESSION['uId'], $_SESSION['lastVisitedPresepe'])) {
        $query = 'DELETE FROM likes WHERE uId='.$_SESSION['uId'].' AND pId= '.$_SESSION['lastVisitedPresepe'];
        $result = $connection->query($query);
        header('location: ../presepe.php?presepeId='.$_SESSION['lastVisitedPresepe']);
      } else  {
        $query = 'INSERT INTO likes (uId, pId) VALUES ('.$_SESSION['uId'].' , '.$_SESSION['lastVisitedPresepe'].')';
        $result = $connection->query($query);
        header('location: ../presepe.php?presepeId='.$_SESSION['lastVisitedPresepe']);
      }
      $connection->close();
  }
  ?>