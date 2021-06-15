<?php

function createPresepePost($row, $connection) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<titlePlaceholder />', '<h2>Nome: '.$row['presepeName'] .'</h2>', $post);
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
?>