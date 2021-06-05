<?php

function createPresepePost($row) {
    $post = file_get_contents(__DIR__.'/../content/common/_presepePost.html');
    $post = str_replace('<authorPlaceHolder />', '<p>'.$row['username'].'</p>', $post);
    $post = str_replace('<datePlaceHolder />', '<p>'.$row['dateOfCreation'].'</p>', $post);
    $post = str_replace('<titlePlaceholder />', '<p>'.$row['presepeName'].'</p>', $post);
    $post = str_replace('<descriptionPlaceHolder />', '<p>'.$row['description'].'</p>', $post);
    $imgPath = $row['photoPath'];
    $img = '<img src="'.$imgPath.'"/>';
    $post = str_replace('<imagePlaceHolder />', $img, $post);
    return($post);
}

function createPresepeList($connection) {
    $query = 'SELECT * from presepi INNER JOIN user on presepi.uId = user.id';
    $result = $connection->query($query);
    $returnValue = '';
    while($row = mysqli_fetch_assoc($result)) {
        $returnValue .= createPresepePost($row);
    }
    return($returnValue);
}
?>