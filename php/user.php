<?php
    session_start();
    require_once("../php/templateBuilder.php");
    require_once('scripts/checkUserConnected.php');
    require_once('scripts/createPresepeList.php');
    require_once('scripts/connection.php');
    require_once('scripts/lastVisitedPages.php');
    handleVisitedPages('./user.php');

    $builder = new TemplateBuilder("/common/_pageTemplate", "..");
    
    if(isset($_SESSION['uId'])) {
        $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "user", $_SESSION["uName"]);
        $builder->setDescription("Pagina personale, di visualizzazione e modifica delle informazioni, dell'utente " . $_SESSION["uName"]);
    } else {
        $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "user", "utente");
        $builder->setDescription("Pagina personale, di visualizzazione e modifica delle informazioni, dell'utente");
    }
    $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
    $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array('<li class="current" aria-current="page">Pagina personale ' . $_SESSION["uName"] . '</li>'));
    $page = $builder->build();
    $page = str_replace('<a href="../php/user.php" role="button">' . $_SESSION["uName"] . '</a>', ' <span id="spanUser">' . $_SESSION["uName"] . '</span>', $page);
    $page = str_replace('<main id="content">', '<main id="content" class="mainUtente">', $page);

    if(!isset($_SESSION['uId'])) {
        header('location: ../index.php');
    }
    else {
        $page = str_replace('<placeholderContent />', file_get_contents(__DIR__.'/content/user.html'), $page);
    }

    if($_SESSION["loggedin"]=="admin") {
        $page = str_replace('<placeholderUserType />', 'admin', $page);
        $page = str_replace('<placeholderAdmin />',
                            '<p>In qualità di amministratore hai la possibilità di eleggere i presepi vincitori del concorso,
                            eliminarli e moderare i commenti direttamente dalle pagine dei singoli presepi presenti in
                            <a href="../php/presepiInGara.php">Presepi in Gara</a></p>', $page);
        $page = str_replace('<placeholderAlert />',
                            '<p>Prima di procedere, sei pregato di inserire correttamente la
                            <span lang="en">password</span>.</p>', $page);          
    }
    else if($_SESSION["loggedin"]=="users") {
        $page = str_replace('<placeholderUserType />', 'utente', $page);
        $page = str_replace('<placeholderAdmin />', '', $page);
        $page = str_replace('<placeholderAlert />',
                            '<p><strong>Attenzione!</strong>Eliminando il profilo rimuoverai tutti i caricamenti e verrai automaticamente
                            escluso dal concorso!<br />Per questo, prima di procedere, sei pregato di inserire correttamente la
                            <span lang="en">password</span>.</p>', $page);    
    }

    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]=="done") {
        $page = str_replace('<placeholderMod />', '<p class="successMsg" tabindex="1">Informazioni modificate con successo!</p>', $page);
        unset($_SESSION["editMsg"]);
    } else {
        $page = str_replace('<placeholderMod />', '', $page);
    }

    if(key_exists("editMsg", $_SESSION) && $_SESSION["editMsg"]!="done") {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg" tabindex="1">La modifica non è andata a buon fine! Ricontrolla i campi.</p>', $page);

        // Controllo nome
        if($_SESSION["editMsg"]=="notValidName") {
            $page = str_replace('<nameHint />', '<p class="errorMsg" tabindex="0">Verifica che il nome inserito abbia una lunghezza
                                compresa tra i tre e ventiquattro caratteri, che inizi con una lettera e che non contenga simboli speciali!</p>', $page);
        }
        // Controllo cognome
        else if($_SESSION["editMsg"]=="notValidSurname") {
            $page = str_replace('<surnameHint />', '<p class="errorMsg" tabindex="0">Verifica che il cognome inserito abbia una lunghezza
                                compresa tra i tre e ventiquattro caratteri, che inizi con una lettera e che non contenga simboli speciali!</p>', $page);
        }
        // Controllo username
        else if($_SESSION["editMsg"]=="notValidUser") {
            $page = str_replace('<usernameHint />', '<p class="errorMsg" tabindex="01">Verifica che lo <span lang="en">username</span> inserito abbia una lunghezza
                                compresa tra i tre e dieci caratteri, che inizi con una lettera e che non contenga simboli speciali!</p>', $page);
        }
        else if($_SESSION["editMsg"]=="alreadyExistingUser") {
            $page = str_replace('<usernameHint />', '<p class="errorMsg" tabindex="0">Lo <span lang="en">username</span> inserito è già stato usato!</p>', $page);
        }
        // Controllo mail
        else if($_SESSION["editMsg"]=="notValidMail") {
            $page = str_replace('<mailHint />', '<p class="errorMsg" tabindex="0">La <span lang="en">mail</span> inserita non è valida!</p>', $page);
        }
        else if($_SESSION["editMsg"]=="alreadyExistingMail") {
            $page = str_replace('<mailHint />', '<p class="errorMsg" tabindex="0">La <span lang="en">mail</span> inserita è già stata usata!</p>', $page);
        }
        // Controllo password
        else if($_SESSION["editMsg"]=="notValidPassword") {
            $page = str_replace('<passwordHint />', '<p class="errorMsg" tabindex="0">Verifica che la nuova <span lang="en">password</span> inserita abbia una lunghezza
                                compresa tra i quattro e sessantaquattro caratteri!</p>', $page);
        }
        // Controllo campi vuoto (ridondante, farebbe già html5)
        else if($_SESSION["editMsg"]=="empty") {
            $page = str_replace('<passwordHint />', '<p class="errorMsg" tabindex="0">Controlla di aver riempito il campo!</p>', $page);
        }
        unset($_SESSION["editMsg"]);
    }
    else {
        $page = str_replace('<nameHint />', '', $page);
        $page = str_replace('<surnameHint />', '', $page);
        $page = str_replace('<usernameHint />', '', $page);
        $page = str_replace('<mailHint />', '', $page);
        $page = str_replace('<passwordHint />', '', $page);
    }
    
    // Password errata in form eliminazione
    if(key_exists("wrongPwd", $_SESSION) && $_SESSION["wrongPwd"]) {
        $page = str_replace('<placeholderErr />', '<p class="errorMsg" tabindex="1">La <span lang="en">password</span> inserita è errata!</p>', $page);
        unset($_SESSION["wrongPwd"]);
    }
    else {
        $page = str_replace('<placeholderErr />', '', $page);
    }

    $page = str_replace('<placeholderUsername />', $_SESSION["uName"], $page);
    $page = str_replace('<placeholderName />', $_SESSION["uRealName"], $page);
    $page = str_replace('<placeholderSurname />', $_SESSION["uSurname"], $page);
    $page = str_replace('<placeholderMail />', $_SESSION["uMail"], $page);
    echo($page);
?>