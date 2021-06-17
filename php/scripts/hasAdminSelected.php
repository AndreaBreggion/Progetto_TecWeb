<?php
  function hasAdminSelected($connection, $uId, $id) {
      $stmt = mysqli_stmt_init($connection);
      $query = 'SELECT winner FROM presepi WHERE uId = ? AND id = ?';
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
      return false;
  }
?>