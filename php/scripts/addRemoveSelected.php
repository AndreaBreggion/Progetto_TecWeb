<?php
require_once('hasAdminSelected.php');
require_once('connection.php');

session_start();
if(!isset($_SESSION['uId'])) {
    header('location: ../login.php');
    exit;
} else {
    $connection = connect();
    $query = "SELECT MAX uId FROM presepi WHERE Id = ".$_SESSION['lastVisitedPresepe'];
    $result = $connection->query($query);//torna tutti i presepi dell'user
    echo($result);
    if(isset($_SESSION["uName"]) && $_SESSION["loggedin"]=='admin') {//se sei admin
        if (hasAdminSelected($connection, $result, $_SESSION['lastVisitedPresepe'])) {//se il presepe è vincitore
            $query = 'UPDATE presepi SET winner=false WHERE uId= '. $result .' AND Id= ' . $_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        } else {//altrimenti
            $query = "UPDATE presepi SET winner=false WHERE uId= '$result' AND Id= ".$_SESSION['lastVisitedPresepe'];
            $result = $connection->query($query);
            header('location: ../presepe.php?presepeId=' . $_SESSION['lastVisitedPresepe']);
        }
    }else{
        exit;
    }
    $connection->close();
}
?>