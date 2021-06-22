<?php
  require_once("templateBuilder.php");
  require_once('scripts/lastVisitedPages.php');
  require_once('scripts/checkUserConnected.php');
  handleVisitedPages('/php/404.php');
  session_start();

  $builder = new TemplateBuilder("/common/_pageTemplate", "..");
  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Errore</span></li>'
  ));
  $page = $builder->build();

  $page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__."/content/common/_404.html"), $page);
  echo($page);
?>
