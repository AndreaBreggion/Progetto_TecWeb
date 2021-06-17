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
        if (hasAdminSelected($connection, $_SESSION['uId'], $_SESSION['lastVisitedPresepe'])) {
            $query = 'UPDATE presepi SET winner=true WHERE uId=' . $_SESSION['uId'] . ' AND pId= ' . $_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        } else {
            $query = 'UPDATE presepi SET winner=true WHERE uId=' . $_SESSION['uId'] . ' AND pId= ' . $_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        }
    }else{
        //non dovresti essere nemmeno qui
        exit;
    }
    $connection->close();
}
?>