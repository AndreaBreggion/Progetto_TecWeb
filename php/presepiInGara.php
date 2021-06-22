<?php
  session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/checkUserConnected.php');
  require_once('scripts/createPresepeList.php');
  require_once('scripts/connection.php');
  require_once('scripts/lastVisitedPages.php');
  handleVisitedPages('/php/presepiInGara.php');
  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("/common/_pageTemplate", "..");

  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  if(isset($_GET['search'])){
    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
      '<li><a href="../index.php" lang="en">Home</a></li>',
      '<li><a href="/php/presepiInGara.php">Presepi in gara</a></li>',
      '<li class="current" aria-current="page"><span class="currentCrumb">Ricerca: '.htmlspecialchars($_GET['search']).'</span></li>'
    ));
  } else $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Presepi in gara</span></li>'
  ));

  $page = $builder->build();
  if(isset($_GET['search'])) {
    $page = str_replace('<li><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', '<li class="current" aria-current="page"><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', $page);
  } else $page = str_replace('<li><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Presepi in Gara</span></li>', $page);
  $page = str_replace('<main id="content">', '<main id="content" class="mainPresepi">', $page);
  $page = str_replace('<placeholderContent></placeholderContent>',
                      '<h2 class="sectionTitle">Presepi attualmente in gara</h2><ul class="listaPresepi"><placeholderLista /></ul>', $page);

  $connection = connect();
  $replacement = isset($_GET['search']) ? createPresepeSearchList($connection, $_GET['search']) : createPresepeList($connection);
  $replacement = strlen($replacement) == 0 ? '<p tabindex="1">Non è ancora stato caricato alcun presepe!</p>' : $replacement;
  $connection->close();
  $page = str_replace('<placeholderLista />', $replacement, $page);
  echo($page);
?>