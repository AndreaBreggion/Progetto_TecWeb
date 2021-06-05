<?php
session_start();
require_once("../php/templateBuilder.php");
require_once('scripts/checkUserConnected.php');
// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder("/common/_pageTemplate", "..");

$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
  '<li><a href="../index.php" lang="en">Home</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb">Presepi in gara</span></li>'
));
$page = $builder->build();
$page = str_replace('<main id="content">', '<main id="content" class="mainPresepi">', $page);
$page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__.'/content/common/presepeTest.html') .file_get_contents(__DIR__.'/content/common/presepeTest.html'), $page);
echo($page);
?>