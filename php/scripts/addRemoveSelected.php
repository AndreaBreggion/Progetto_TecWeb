<?php
require_once('hasAdminSelected.php');
require_once('connection.php');

session_start();
if(!isset($_SESSION['uId'])) {
    header('location: ../login.php');
    exit;
} else {
    $connection = connect();
    $query = "SELECT MAX uId as id FROM presepi WHERE Id = ".$_SESSION['lastVisitedPresepe'];//qui deve prendere il presepe della scheda
    //  $query = 'SELECT uId as id FROM presepi WHERE id = '.$_SESSION['lastVisitedPresepe'];
    $result = $connection->query($query);
    //echo "<h1>".$result."</h1>";
    if(isset($_SESSION["uName"]) && $_SESSION["loggedin"]=='admin') {//se sei admin
        if (hasAdminSelected($connection, $result, $_SESSION['lastVisitedPresepe'])) {//se il presepe è vincitore
            $query = "UPDATE presepi SET winner=false WHERE uId= '$result' AND Id= ".$_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        } else {//altrimenti
            $query = "UPDATE presepi SET winner=true WHERE uId= '$result' AND Id= ".$_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        }
    }else{
        exit;
    }
    $connection->close();
}
?>