<?php

function createPresepePost($row, $connection) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<authorPlaceHolder />', '<h4>'.$row['presepeName'].'</h4>', $post);
    $post = str_replace('<datePlaceHolder />', '<p class="postDate">'.$row['dateOfCreation'].'</p>', $post);
    $post = str_replace('<titlePlaceholder />', '<h5>'.$row['username'] .' - categoria '. $row['category'].'</h5>', $post);
    $post = str_replace('<descriptionPlaceHolder />', '<p>'.$row['description'].'</p>', $post);
    $img = $row['photoPath'];
    $post = str_replace('<placeholderImage />', $img, $post);
    $post = str_replace('<presepeLinkPlaceholder />', '<a href="/php/presepe.php?presepeId='.$row['id'].'">Scopri di più</a>', $post);
    $query = 'SELECT COUNT(*) FROM likes WHERE pId ='. $row['id'];
    $count = $connection->query($query);
    $result = mysqli_fetch_array($count, MYSQLI_NUM);
    $post = str_replace('<likeNumberPlaceHolder/>', $result[0], $post);
    return($post);
}

function createPresepeList($connection) {
    $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id';
    $result = $connection->query($query);
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue .= createPresepePost($row, $connection);
    }
    return($returnValue);
}

function createPresepeSearchList($connection, $where) {
  $where = trim($where);
  $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.presepeName LIKE ?';
  $stmt = mysqli_stmt_init($connection);
  if( !mysqli_stmt_prepare($stmt, $query) ) {
    exit;
  }
  $like = '%'.$where."%";
  mysqli_stmt_bind_param($stmt, 's', $like);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  mysqli_stmt_close($stmt);
  $returnValue = '';
  $i = 0;
  while($row = mysqli_fetch_assoc($result)) {
    $i++;
    $returnValue .= createPresepePost($row, $connection);
  }
  if(strlen($returnValue) == 0) {
    $returnValue = '<h2 tabindex="1"><em> La ricerca di '. htmlspecialchars($where) . ' non ha prodotto risultati!</em></h2>';
  } else if($i == 1) {
    $returnValue = '<p tabindex="0" class="searchResult"> La tua ricerca ha prodotto 1 risultato! </p>' . $returnValue;
  } else $returnValue = '<p tabindex="0" class="searchResult"> La tua ricerca ha prodotto '.$i.' risultati! </p>' . $returnValue;
  return($returnValue);
}
?>