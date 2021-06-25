<?php

function createPresepePost($row, $connection) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<titlePlaceholder />', '<h3 class="postTitolo">' .$row['presepeName'] .'</h3>', $post);
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
    $post = str_replace('<presepeLinkPlaceholder />', '<a href="./presepe.php?presepeId='.$row['id'].'">Scopri di più</a>', $post);
    $query = 'SELECT COUNT(*) FROM likes WHERE pId ='. $row['id'];
    $count = $connection->query($query);
    $result = mysqli_fetch_array($count, MYSQLI_NUM);
    $post = str_replace('<likeNumberPlaceHolder/>', $result[0], $post);
    return($post);
}

function createPresepeList($connection) {
    $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id ORDER BY presepi.dateOfCreation';
    $result = $connection->query($query);
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue = createPresepePost($row, $connection) . $returnValue;
    }
    return($returnValue);
}

function createPresepeListRaga($connection) {
  $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.category = "ragazzi"';
  $result = $connection->query($query);
  $returnValue = '';
  while($row = mysqli_fetch_assoc($result)) {
    $returnValue = createPresepePost($row, $connection) . $returnValue;
  }
  return($returnValue);
}

function createPresepeListAdu($connection) {
  $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.category = "adulti"';
  $result = $connection->query($query);
  $returnValue = '';
  while($row = mysqli_fetch_assoc($result)) {
    $returnValue = createPresepePost($row, $connection) . $returnValue;
  }
  return($returnValue);
}

function createPresepeListAlph($connection) {
  $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id ORDER BY presepi.presepeName DESC';
  $result = $connection->query($query);
  $returnValue = '';
  while($row = mysqli_fetch_assoc($result)) {
    $returnValue = createPresepePost($row, $connection) . $returnValue;
  }
  return($returnValue);
}

function createPresepeListLike($connection) {
  $query = 'SELECT pId, COUNT(*) FROM likes GROUP BY pId ORDER BY COUNT(*) DESC';
  $result = $connection->query($query);
  $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.id = ?';
  $stmt = mysqli_stmt_init($connection);
  if( !mysqli_stmt_prepare($stmt, $query) ) {
    exit;
  }
  $returnValue = '';
  while($row = mysqli_fetch_assoc($result)){
    mysqli_stmt_bind_param($stmt, 's', $row['pId']);
    mysqli_stmt_execute($stmt);
    $presepe = mysqli_stmt_get_result($stmt);
    $presepe = mysqli_fetch_assoc($presepe);
    $returnValue .= createPresepePost($presepe, $connection);
  }
  mysqli_stmt_close($stmt);
  return $returnValue;
}

function createPresepeSearchList($connection, $where) {
  $where = trim($where);
  $where = substr($where, 0, 48);
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
    $returnValue = '<li><p tabindex="1"><em> La ricerca di '. htmlspecialchars($where) . ' non ha prodotto risultati!</em></p></li>';
  } else if($i == 1) {
    $returnValue = '<li><p tabindex="0" class="searchResult"> La tua ricerca ha prodotto 1 risultato! </p></li>' . $returnValue;
  } else $returnValue = '</li><p tabindex="0" class="searchResult"> La tua ricerca ha prodotto '.$i.' risultati! </p></li>' . $returnValue;
  return($returnValue);
}


function createPresepeListRagazzi($connection) {
    $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.winner=1 AND presepi.category = "ragazzi"';
    $result = $connection->query($query);
    if(!$result) return('');
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue .= createPresepePost($row, $connection);
    }
    return($returnValue);
}

function createPresepeListAdulti($connection) {
    $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.winner=1 AND presepi.category = "adulti"';
    $result = $connection->query($query);
    if(!$result) return('');
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue .= createPresepePost($row, $connection);
    }
    return($returnValue);
}

function mostLiked($connection) {
    $query = 'SELECT pId, COUNT(*) FROM likes GROUP BY pId ORDER BY COUNT(*) DESC LIMIT 3';
    $result = $connection->query($query);
    $query = 'SELECT presepi.*, users.username as username, users.id as UID from presepi INNER JOIN users on presepi.uId = users.id WHERE presepi.id = ?';
    $stmt = mysqli_stmt_init($connection);
    if( !mysqli_stmt_prepare($stmt, $query) ) {
        exit;
    }
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)){
        mysqli_stmt_bind_param($stmt, 's', $row['pId']);
        mysqli_stmt_execute($stmt);
        $presepe = mysqli_stmt_get_result($stmt);
        $presepe = mysqli_fetch_assoc($presepe);
        $returnValue .= createPresepePost($presepe, $connection);
    }
    mysqli_stmt_close($stmt);
    $returnValue = str_replace('<a href="./presepe.php?presepeId=', '<a href="./php/presepe.php?presepeId=', $returnValue);
    $returnValue = str_replace('<img src="../sources/images/', '<img src="./sources/images/', $returnValue);
    
    return $returnValue;
}

?>
