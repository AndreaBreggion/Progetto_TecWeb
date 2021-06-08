<?php
  function hasUserLiked($connection, $uId, $id) {
    $stmt = mysqli_stmt_init($connection);
    $query = 'SELECT * FROM likes WHERE uId = ? AND pId = ?';
    if( !mysqli_stmt_prepare($stmt, $query) ) {
      exit;
    }
    mysqli_stmt_bind_param($stmt, 'ii', $uId, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if($row = mysqli_fetch_array($result)){
      return true;
    } else {
      return false;
    }
  }
?>