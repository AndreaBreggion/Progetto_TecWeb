<?php
  function hasAdminSelected($connection, $id) {
      $query = 'SELECT winner FROM presepi WHERE id = '. $id;
      //$connection->query("DELETE FROM users WHERE users.id = '$uID'");
      $result = $connection->query($query);
      $result = mysqli_fetch_assoc($result);
      if ($result['winner']) {
          //echo("Ok questo è vincitore");
          return true;
      } else {
          return false;
      }

  }
?>