<?php

function createPresepePost($row, $connection) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<authorPlaceHolder />', '<h4>'.$row['username'].'</h4>', $post);
    $post = str_replace('<datePlaceHolder />', '<p class="postDate">'.$row['dateOfCreation'].'</p>', $post);
    $post = str_replace('<titlePlaceholder />', '<h5>'.$row['presepeName'] .' - categoria '. $row['category'].'</h5>', $post);
    $post = str_replace('<descriptionPlaceHolder />', '<p>'.$row['description'].'</p>', $post);
    $img = $row['photoPath'];
    $post = str_replace('<imagePlaceHolder />', $img, $post);
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