<?php
  function hasAdminSelected($connection, $uId, $id) {
      $query = 'SELECT EXISTS (SELECT * FROM presepi WHERE uId = ".$uId."  AND pId = ".$id."  AND presepi.winner=1)';
      $result = $connection->query($query);
      if ($result) {
          //echo("Ok questo è vincitore");
          return true;
      } else {
          return false;
      }

  }
?>