<?php
session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/lastVisitedPages.php');
handleVisitedPages('./regole.php');

$builder = new TemplateBuilder("/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "regole");
$builder->setDescription("Pagina che espone le regole di partecipazione al Concorso Presepi di Farra di Soligo");
$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(  '<li><a href="../index.php" lang="en">Home</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb">Regole del concorso</span></li>'));
$page = $builder->build();
$page = str_replace('<li><a href="../php/regole.php">Regole del concorso</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Regole del concorso</span></li>', $page);
$page = str_replace('<placeholderContent />', file_get_contents(__DIR__."/content/common/_regoleContent.html"), $page);
echo($page);
?>