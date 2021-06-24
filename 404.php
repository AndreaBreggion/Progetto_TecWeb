<?php
  session_start();
  require_once("/php/templateBuilder.php");
  require_once('php/scripts/checkUserConnected.php');
  require_once('php/scripts/connection.php');
  require_once('php/scripts/statementQuery.php');
  require_once('php/scripts/lastVisitedPages.php');
  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("content/common/_pageTemplate", "..");

  $builder->setHead(file_get_contents(__DIR__."/php/content/common/_head.html"), "404");
  $builder->setDescription("404");
  $builder->setHeader(file_get_contents(__DIR__."/php/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/php/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/php/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">404</span></li>'
  ));

  $page = $builder->build();

  $page = str_replace('<placeholderContent />', file_get_contents(__DIR__."/php/content/common/_404.html"), $page);
  echo($page);
?>
