<?php
  function statementQuery($connection, $where, $query) {
    $stmt = mysqli_stmt_init($connection);
    if( !mysqli_stmt_prepare($stmt, $query) ) {
      exit;
    }
    mysqli_stmt_bind_param($stmt, 's', $where);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    if($row = mysqli_fetch_assoc($result)){
      return $row;
    } else {
      return false;
    }
  }
?>
