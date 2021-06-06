<?php

function createPresepePost($row) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<authorPlaceHolder />', '<h4>'.$row['username'].'</h4>', $post);
    $post = str_replace('<datePlaceHolder />', '<p class="postDate">'.$row['dateOfCreation'].'</p>', $post);
    $post = str_replace('<titlePlaceholder />', '<h5>'.$row['presepeName'] .' - categoria '. $row['category'].'</h5>', $post);
    $post = str_replace('<descriptionPlaceHolder />', '<p>'.$row['description'].'</p>', $post);
    $imgPath = $row['photoPath'];
    $img = '<img src="'.$imgPath.'"/>';
    $post = str_replace('<imagePlaceHolder />', $img, $post);
    $post = str_replace('<presepeLinkPlaceholder />', '<a href="/php/presepe.php?presepeId='.$row['id'].'">Scopri di più</a>', $post);
    return($post);
}

function createPresepeList($connection) {
    $query = 'SELECT presepi.*, user.username as username, user.id as UID from presepi INNER JOIN user on presepi.uId = user.id';
    $result = $connection->query($query);
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue .= createPresepePost($row);
    }
    return($returnValue);
}
?>