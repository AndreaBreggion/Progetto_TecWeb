<?php
    session_start();
    require_once("../php/templateBuilder.php");
    require_once('scripts/checkUserConnected.php');
    require_once('scripts/createPresepeList.php');
    require_once('scripts/connection.php');
    require_once('scripts/lastVisitedPages.php');
    // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
    $builder = new TemplateBuilder("/common/_pageTemplate", "..");

    $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

    $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());

    $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));

    handleVisitedPages('/php/vincitori.php');

    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
        '<li><a href="../index.php" lang="en">Home</a></li>',
        '<li class="current" aria-current="page"><span class="currentCrumb">Vincitori</span></li>'
    ));

    $page = $builder->build();
    $page = str_replace('<li><a href="../php/vincitori.php">Vincitori</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Vincitori</span></li>', $page);
    $page = str_replace('<main id="content">', '<main id="content" class="mainPresepi">', $page);
    $page = str_replace('<placeholderContent />', '<ul class="listaPresepi"><placeholderLista /></ul>', $page);
    $page = str_replace('<ul class="listaPresepi"><placeholderLista /></ul>', file_get_contents(__DIR__."/content/common/_presepiWinnerContent.html"), $page);

    $connection = connect();
    $replacement = createPresepeListRagazzi($connection);
    $replacement = strlen($replacement) == 0 ? '<p>I vincitori della categoria Ragazzi non sono ancora stati eletti.</p>' : $replacement;
    $page = str_replace('<placeholderRagazzi />', $replacement, $page);
    $replacement = createPresepeListAdulti($connection);
    $replacement = strlen($replacement) == 0 ? '<p>I vincitori della categoria Adulti non sono ancora stati eletti.</p>' : $replacement;
    $page = str_replace('<placeholderAdulti />', $replacement, $page);
    echo($page);
?>