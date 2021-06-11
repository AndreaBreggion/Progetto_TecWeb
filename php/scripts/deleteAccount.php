<?php
    session_start();
    require_once('connection.php');
    require_once('statementQuery.php');
    $connection = connect();

    $username = $_SESSION["uName"];
    $uID = $_SESSION["uId"];
    $insertedPwd = $_POST["passwordEliminaProfilo"];


    $query = "SELECT * FROM users WHERE users.id = ?";
    $data = statementQuery($connection, $uID, $query);

    if($data) {
        $passwordCheck = password_verify($insertedPwd, $data['password']);
    }
    
    if(isset($_POST["deleteAccount"]) && $passwordCheck) {
        $connection->query("DELETE FROM users WHERE users.id = '$uID'");
        unset($_SESSION['loggedin']);
        session_destroy();
        header('location: ../../index.php');
        exit;
        
    } else {
        $_SESSION["wrongPwd"] = true;
        header("Location: ../user.php");
        exit;
    }
?>