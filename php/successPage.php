<?php
session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');

// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder("/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "successo");
$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(  '<li><a href="../index.php" lang="en">Home</a></li>',
  '<li><a href="aggiungiPresepe.php">Aggiungi presepe</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb">Conferma caricamento</span></li>'));
$page = $builder->build();
$page = str_replace('<placeholderContent />',
                    '<h2 class="sectionTitle">Conferma caricamento</h2>
                     <p class="successPageMsg" tabindex="1">Il tuo presepe è stato caricato con successo!</p>', $page);
echo($page);
?>