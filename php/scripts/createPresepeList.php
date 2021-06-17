<?php

function createPresepePost($row, $connection) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<titlePlaceholder />', '<h2>' .$row['presepeName'] .'</h2>', $post);
    $post = str_replace('<categoryPlaceholder />', '<p><span class="postCategoria">Categoria:</span> '. $row['category']. '</p>', $post);
    $post = str_replace('<datePlaceHolder />', '<p><span class="postData">Data di caricamento:</span> '.$row['dateOfCreation'].'</p>', $post);
    $post = str_replace('<authorPlaceHolder />', '<p><span class="postAutore">Autore:</span> '.$row['username'].'</p>', $post);
    $presepeName = $row['presepeName'];
    $presepeName = str_replace(' ', '', $presepeName);
    $img = $row['photoPath'];
    $longdesc = str_replace('.', '', $img);
    $post = str_replace('<descriptionPlaceHolder />',
                        '<p class="postDescrizione" id="#' . $longdesc . 'longdesc"><span>Descrizione:</span><br />' . $row['description'] .'</p>', $post);
    $post = str_replace('<placeholderImage />', $img, $post);
    $post = str_replace('<placeholderAlt />', 'Foto del presepe ' . $row['presepeName'], $post);
    $post = str_replace('<placeholderLongdesc />', '#' .$longdesc . 'longdesc' , $post);
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