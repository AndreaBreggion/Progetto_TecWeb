<?php
    session_start();
    require_once('connection.php');
    require_once('statementQuery.php');
    $connection = connect();

    $uID = $_SESSION["uId"];

    if(isset($_POST["updateInformations"])) {
        // Nome
        if(isset($_POST["nameInformation"])) {
            $newName = trim($_POST["nameInformation"]);
            if(strlen($newName)<3 || strlen($newName)>24 || preg_match('/[0-9!#$%&\'*+\=?^_\`{|}~\-]/', $newName)) {
                $_SESSION["editMsg"] = "notValidName";
            }
            else {
                $connection->query("UPDATE users SET name ='$newName' WHERE id='$uID'");
                $_SESSION["uRealName"] = $newName;
                $_SESSION["editMsg"] = "done";
            }
        }
        // Cognome
        else if(isset($_POST["surnameInformation"])) {
            $newSurname = trim($_POST["surnameInformation"]);
            if(strlen($newSurname)<3 || strlen($newSurname)>24 || preg_match('/[0-9!#$%&\'*+\=?^_\`{|}~\-]/', $newSurname)) {
                $_SESSION["editMsg"] = "notValidSurname";
            }
            else {
                $connection->query("UPDATE users SET surname ='$newSurname' WHERE id='$uID'");
                $_SESSION["uSurname"] = $newSurname;
                $_SESSION["editMsg"] = "done";
            }
        }
        // Username
        else if(isset($_POST["usernameInformation"])) {
            $currentUsername = $_SESSION["uName"];
            $newUsername = trim($_POST["usernameInformation"]);
            $isUsernameAlreadyTaken = statementQuery($connection, $newUsername, "SELECT * FROM users WHERE username = ? AND username <> '$currentUsername'");
            if(strlen($newUsername)<3 || strlen($newUsername)>10 || !preg_match('/^[a-zA-Z][a-zA-Z0-9.,$;]+$/', $newUsername)) {
                $_SESSION["editMsg"] = "notValidUser";
            }
            else if($isUsernameAlreadyTaken) {
                $_SESSION["editMsg"] = "alreadyExistingUser";
            }
            else {
                $connection->query("UPDATE users SET username ='$newUsername' WHERE id='$uID'");
                $_SESSION["uName"] = $newUsername;
                $_SESSION["editMsg"] = "done";
            }
        }
        // Mail
        else if(isset($_POST["mailInformation"])) {
            $currentMail = $_SESSION["uMail"];
            $newMail = trim($_POST["mailInformation"]);
            $isMailAlreadyRegistered = statementQuery($connection, $newMail, "SELECT * FROM users WHERE mail = ? AND mail <> '$currentMail'");
            if(!preg_match('/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $newMail)) {
                $_SESSION["editMsg"] = "notValidMail";
            }
            else if($isMailAlreadyRegistered) {
                $_SESSION["editMsg"] = "alreadyExistingMail";
            }
            else if(empty($newMail)) {
                $_SESSION["editMsg"] = "empty";
            }
            else {
                $connection->query("UPDATE users SET mail ='$newMail' WHERE id='$uID'");
                $_SESSION["uMail"] = $newMail;
                $_SESSION["editMsg"] = "done";
            }
        }
        // Password
        else if(isset($_POST["oldPasswordInformation"]) && isset($_POST["newPasswordInformation"])) {
            $oldPwd = trim($_POST["oldPasswordInformation"]);
            $newPwd = trim($_POST["newPasswordInformation"]);
            $data = statementQuery($connection, $uID, "SELECT * FROM users WHERE id = ?");
            $passwordCheck = false;
            if($data) {
                $passwordCheck = password_verify($oldPwd, $data["password"]);
            }
            if(strlen($newPwd)<4 || strlen($newPwd)>64) {
                $_SESSION["editMsg"] = "notValidPassword";
            }
            else if(!$passwordCheck) {
                $_SESSION["wrongPwd"] = true;
            }
            else {
                $newPwd = password_hash($newPwd, PASSWORD_DEFAULT);
                $connection->query("UPDATE users SET password ='$newPwd' WHERE id='$uID'");
                $_SESSION["editMsg"] = "done";
            }
        }
    }

    header('location: ../user.php');
    exit;
?>