<?php
if($_SERVER['REQUEST_URI'] !=$_SERVER['PHP_SELF']) {
  header("Location: ".$_SERVER['PHP_SELF']);
}

session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/checkUserConnected.php');
  require_once('scripts/connection.php');
  require_once('scripts/statementQuery.php');
  require_once('scripts/lastVisitedPages.php');
  $builder = new TemplateBuilder("/common/_pageTemplate", "..");

  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "404");
  $builder->setDescription("404");
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">404</span></li>'
  ));

  $page = $builder->build();

  $page = str_replace('<placeholderContent />', file_get_contents(__DIR__."/content/common/_404.html"), $page);
  echo($page);
?>
