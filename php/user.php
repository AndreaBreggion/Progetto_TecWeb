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
    $page = str_replace('<placeholderNome />', $_SESSION["uName"], $page);

    echo($page);
?>