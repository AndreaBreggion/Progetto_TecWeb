<?php
  session_start();
  require_once("php/templateBuilder.php");
  require_once('php/scripts/checkUserConnected.php');
  require_once('php/scripts/lastVisitedPages.php');
  require_once('php/scripts/connection.php');
  require_once('php/scripts/createPresepeList.php');
  handleVisitedPages('/index.php');

  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("home", ".");
  $builder->setHead(file_get_contents(__DIR__."/php/content/common/_head.html"), "home");
  $builder->setHeader(file_get_contents(__DIR__."/php/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/php/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/php/content/common/_breadcrumbs.html"), array('<li class="current" aria-current="page"><span lang="en">Home</span></li>'));
  $page = $builder->build();
  $page = str_replace('<h1><a href="../index.php">I presepi di Farra di Soligo</a></h1>', '<h1>I presepi di Farra di Soligo</h1>', $page);
  $page = str_replace('<li><a lang="en" href="./index.php">Home</a></li>', '<li class="current" aria-current="page"><span class="currentPage" lang="en">Home</span></li>', $page);
  $connection = connect();
  $replacement = mostLiked($connection);
  $replacement = strlen($replacement) == 0 ? '<p tabindex="1">Non è ancora stato votato alcun presepe.</p>' : $replacement;
  $page = str_replace('<mostLikedPlaceholder />', $replacement, $page);
  echo($page);
?>