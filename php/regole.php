<?php
session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/lastVisitedPages.php');
handleVisitedPages('/php/regole.php');

// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder("/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));
$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(  '<li><a href="../index.php" lang="en">Home</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb">Regole del concorso</span></li>'));
$page = $builder->build();
$page = str_replace('<li><a lang="en" href="./index.php">Regole del concorso</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Regole del concorso</span></li>', $page);
$page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__."/content/common/_regoleContent.html"), $page);
echo($page);
?>