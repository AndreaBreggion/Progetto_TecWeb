<?php
  session_start();
  require_once('connection.php');
  $timestamp = trim($_POST['timestamp']);
  $username = $_POST['username'];
  if($_SESSION['uId'] == $username || $_SESSION['loggedin'] == 'admin') {
    $query = 'DELETE FROM comments WHERE uId = ? AND pId = ? AND timestamp = ?';
    $connection = connect();
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 'iis', $username, $_SESSION['lastVisitedPresepe'], $_POST['timestamp']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
  }
  header('location: .'.$_SESSION['lastPages'][1]);
?>