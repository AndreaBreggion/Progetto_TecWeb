<?php
    session_start();
    require_once('connection.php');
    require_once('statementQuery.php');
    $connection = connect();


    $uID = $_SESSION["uId"];

    $currentUsername = $_SESSION["uName"];
    $newUsername = trim($_POST["usernameInformation"]);
    $isUsernameAlreadyTaken = statementQuery($connection, $username, "SELECT * FROM users WHERE username = ? AND username <> 'currentUsername'");

    $currentMail = $_SESSION["uMail"];
    $newMail = trim($_POST["emailInformation"]);
    $isMailAlreadyRegistered = statementQuery($connection, $newMail, "SELECT * FROM users WHERE mail = ? AND mail <> '$currentMail'");

        
    if(isset($_POST["updateInformations"])) {
        if(!preg_match('/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $newMail)) {
            $_SESSION["editMsg"] = "notValidMail";
        }
        else if ($isMailAlreadyRegistered) {
            $_SESSION["editMsg"] = "alreadyExistingMail";
        }
        else if(strlen($newUsername)<3 || strlen($newUsername)>10 || !preg_match('/^[a-zA-Z][a-zA-Z0-9.,$;]+$/', $newUsername)) {
            $_SESSION["editMsg"] = "notValidUser";
        }
        else if($isUsernameAlreadyTaken) {
            $_SESSION["editMsg"] = "alreadyExistingUser";
        }
        else if(empty($newMail) || empty($newUsername)) {
            $_SESSION["editMsg"] = "empty";
        }
        else {
            $connection->query("UPDATE users SET mail ='$newMail', username='$newUsername' WHERE id='$uID'");
            $_SESSION["uName"] = $newUsername;
            $_SESSION["uMail"] = $newMail;
            $_SESSION["editMsg"] = "done";
        }
    }
    header('location: ../user.php');
    exit;
?>