<?php
    session_start();
    require_once('connection.php');
    $connection = connect();

    //$passwordCheck = password_verify($_POST['passwordEliminaProfilo'], $data['password']);

    $username = $_SESSION['uName'];
    $uID = $_SESSION['uId'];
    
    if(isset($_POST["deleteAccount"])) {
        $connection->query("DELETE FROM users WHERE users.username = $username");
        unset($_SESSION['loggedin']);
        session_destroy();
        header('location: ../../index.php');
        exit;
    }
?>