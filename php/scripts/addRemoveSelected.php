<?php
require_once('hasAdminSelected.php');
require_once('connection.php');

session_start();
if(!isset($_SESSION['uId'])) {
    header('location: ../login.php');
    exit;
} else {
    $connection = connect();
    if(isset($_SESSION["uName"]) && $_SESSION["loggedin"]=='admin') {//se sei admin
        if (hasAdminSelected($connection, $_SESSION['lastVisitedPresepe'])) {//se il presepe è vincitore
            $query = "UPDATE presepi SET winner = 0 WHERE id = " .$_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        } else {//altrimenti
            $query = "UPDATE presepi SET winner = 1 WHERE id = " .$_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        }
    }else{
        exit;
    }
    $connection->close();
}
?>