<?php
  function hasAdminSelected($connection, $uId, $id) {
      $query = 'SELECT EXISTS (SELECT * FROM presepi WHERE uId = "$uId"  AND pId = "$id"  AND presepi.winner=1)';
      //$connection->query("DELETE FROM users WHERE users.id = '$uID'");
      $result = $connection->query($query);
      if ($result) {
          //echo("Ok questo è vincitore");
          return true;
      } else {
          return false;
      }

  }
?>