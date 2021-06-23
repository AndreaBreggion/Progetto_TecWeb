<?php
  session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/checkUserConnected.php');
  require_once('scripts/createPresepeList.php');
  require_once('scripts/connection.php');
  require_once('scripts/lastVisitedPages.php');
  handleVisitedPages('./presepiInGara.php');
  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("/common/_pageTemplate", "..");

  if(isset($_GET['search'])){
    $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "presepiInGara", "ricerca");
    $builder->setDescription("Pagina che espone la lista dei risultai della ricerca tra i presepi attualmente in gara");
    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
      '<li><a href="../index.php" lang="en">Home</a></li>',
      '<li><a href="/php/presepiInGara.php">Presepi in gara</a></li>',
      '<li class="current" aria-current="page"><span class="currentCrumb">Ricerca: '.htmlspecialchars($_GET['search']).'</span></li>'
    ));
  } else {
    $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "presepiInGara");
    $builder->setDescription("Pagina che espone la lista di tutti i presepi attualmente in gara");
    $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Presepi in gara</span></li>'
    ));
  }
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));

  $page = $builder->build();
  if(isset($_GET['search'])) {
    $page = str_replace('<li><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', '<li class="current" aria-current="page"><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', $page);
  } else $page = str_replace('<li><a href="../php/presepiInGara.php">Presepi in Gara</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Presepi in Gara</span></li>', $page);
  $page = str_replace('<main id="content">', '<main id="content" class="mainPresepi">', $page);

  $page = str_replace('<placeholderContent />',
                      '<h2 class="sectionTitle">Presepi attualmente in gara</h2><ul class="listaPresepi"><placeholderLista /></ul>
                              <ul id="buttonTop"><placeholderButtonTop /></ul>', $page);

  $connection = connect();
  $replacement = isset($_GET['search']) ? createPresepeSearchList($connection, $_GET['search']) : createPresepeList($connection);
  if(isset($_POST['selectCategory'])){
    if($_POST['selectCategory'] == 'adulti') $replacement = createPresepeListAdu($connection);
    if($_POST['selectCategory'] == 'ragazzi') $replacement = createPresepeListRaga($connection);
    if($_POST['selectCategory'] == 'data') $replacement = createPresepeList($connection);
    if($_POST['selectCategory'] == 'nome') $replacement = createPresepeListAlph($connection);
    if($_POST['selectCategory'] == 'like') $replacement = createPresepeListLike($connection);
  }

  $replacement = strlen($replacement) == 0 ? '<p tabindex="1">Non è ancora stato caricato alcun presepe!</p>' : $replacement;
  $connection->close();
  $page = str_replace('<placeholderLista />', $replacement, $page);
  $page = str_replace('<ul id="buttonTop"><placeholderButtonTop /></ul>', file_get_contents(__DIR__."/content/common/_addTopButton.html"), $page);
  echo($page);
?>