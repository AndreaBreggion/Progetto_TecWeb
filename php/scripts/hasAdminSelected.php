<?php
  function hasAdminSelected($connection, $id) {
      $query = 'SELECT winner FROM presepi WHERE id = '. $id;
      $result = $connection->query($query);
      $result = mysqli_fetch_assoc($result);
      if ($result['winner']) {
          return true;
      } else {
          return false;
      }

  }
?>