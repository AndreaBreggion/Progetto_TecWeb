<?php
  session_start();
  require_once('connection.php');
  if(!isset($_SESSION['uId'])) {
    header('location: ../login.php');
    exit;
  } else {
    $connection = connect();
    $query = 'INSERT INTO comments (uId, pId, comment, timeStamp) VALUES (?,?,?,?)';
    $comment = trim($_POST['comment']);
    $comment = htmlspecialchars($comment);
    $datetime = date('Y-m-d h:i:s').'.000';
    $stmt = mysqli_stmt_init($connection);
    if( !mysqli_stmt_prepare($stmt, $query) ) {
      exit;
    }
    mysqli_stmt_bind_param($stmt, 'iiss', $_SESSION['uId'], $_SESSION['lastVisitedPresepe'], $comment, $datetime);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $connection->close();
    header('location: '.$_SESSION['lastPages'][1]);
  }
?>