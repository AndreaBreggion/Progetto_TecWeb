<?php
    session_start();
    require_once("../php/templateBuilder.php");
    require_once('scripts/checkUserConnected.php');
    require_once('scripts/createPresepeList.php');
    require_once('scripts/connection.php');
    require_once('scripts/lastVisitedPages.php');
    handleVisitedPages('/php/user.php');

    // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
    $builder = new TemplateBuilder("/common/_pageTemplate", "..");

    $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

    $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
    $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array('<li class="current" aria-current="location">Utente</li>'));
    $page = $builder->build();
    $page = str_replace('<main id="content">', '<main id="content" class="mainUtente">', $page);



    if(!isset($_SESSION['uId']) || $_SESSION["loggedin"]!='users') {
        header('location: ../index.php');
    }
    else {
        $page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__.'/content/user.html'), $page);
    }

    // Se le informazioni sono state modificate correttamente
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="done") {
        $page = str_replace('<placeholderMod />', '<p class="successMsg">Informazioni modificate con successo!</p>', $page);
        unset($_SESSION["editMsg"]);
    }

    // Controllo nuova mail
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="notValidMail") {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg">La <span xml:lang="en">mail</span> inserita non è valida!</p>', $page);
        unset($_SESSION["editMsg"]);
    }
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="alreadyExistingMail") {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg">La <span xml:lang="en">mail</span> inserita è già stata usata!</p>', $page);
        unset($_SESSION["editMsg"]);
    }

    // Controllo username
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="notValidUser") {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg">Verifica che lo <span xml:lang="en">username</span> abbia una lunghezza
                            compresa tra i tre e dieci caratteri, che inizi con una lettera e che non contenga simboli speciali!</p>', $page);
        unset($_SESSION["editMsg"]);
    }
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="alreadyExistingUser") {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg">Lo <span xml:lang="en">username</span> inserito è già stato usato!</p>', $page);
        unset($_SESSION["editMsg"]);
    }

    // Controllo campi vuoto (ridondante, farebbe già html)
    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="empty") {
        $page = str_replace("<placeholderErr />", "<p class=\"errorMsg\">Controlla di aver riempito tutti i campi!</p>", $page);
        unset($_SESSION["editMsg"]);
    }
    
    // Password errata in form eliminazione
    if(key_exists("wrongPwd", $_SESSION) && $_SESSION["wrongPwd"]) {
        $page = str_replace("<placeholderDel />", "<p class=\"errorMsg\">La <span xml:lang=\"en\">password</span> inserita è errata!</p>", $page);
        unset($_SESSION["wrongPwd"]);
    }


    $page = str_replace('<placeholderUsername />', $_SESSION["uName"], $page);
    $page = str_replace('<placeholderNome />', $_SESSION["uRealName"], $page);
    $page = str_replace('<placeholderCognome />', $_SESSION["uSurname"], $page);
    $page = str_replace('<placeholderUser />', $_SESSION["uName"], $page);
    $page = str_replace('<placeholderMail />', $_SESSION["uMail"], $page);

    echo($page);
?>