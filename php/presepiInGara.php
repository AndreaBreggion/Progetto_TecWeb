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
    $builder->setDescription("Pagina che espone la lista di tutti i presepi attualmente in gara, filtrabili su determinati parametri");
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
  
  $connection = connect(); 
  if(strlen(createPresepeList($connection)) == 0 || isset($_GET['search'])) {
    $filterForm = '';
  } else {
    $filterForm = file_get_contents(__DIR__."/content/common/_filterForm.html");
  }
  $page = str_replace('<placeholderContent />',
                      '<h2 class="sectionTitle">Presepi attualmente in gara</h2>' .
                      '<placeholderSearch />' .
                      $filterForm .
                      '<ul class="listaPresepi"><placeholderLista /></ul>', $page);


  $connection = connect(); 

  if(isset($_GET['search'])) {
    $replacement = createPresepeSearchList($connection, $_GET['search']);
    if($replacement[0] == 0) {
      $searchResult = '<p tabindex="1" class="searchResult">La ricerca di '. htmlspecialchars($_GET['search']) . ' non ha prodotto risultati!</p>';
      $page = str_replace('<ul class="listaPresepi"><placeholderLista /></ul>', '', $page);
      $replacement = $replacement[1];
    } else if($replacement[0] == 1){
      $searchResult = '<p tabindex="0" class="searchResult"> La ricerca di '. htmlspecialchars($_GET['search']) . ' ha prodotto un risultato:</p>';
      $replacement = $replacement[1];
    } else {
      $searchResult = '<p tabindex="0" class="searchResult"> La La ricerca di '. htmlspecialchars($_GET['search']) . ' ha prodotto ' . $replacement[0] . ' risultati:</p>';
      $replacement = $replacement[1];
    }
    $page = str_replace('<placeholderSearch />', $searchResult, $page);
  } else {
    $replacement =  createPresepeList($connection);
    $page = str_replace('<placeholderSearch />', '', $page);
  }
  

  if(isset($_POST['selectFilter']) && $_POST['selectFilter'] != ''){
    if($_POST['selectFilter'] == 'adulti') {
      $page = str_replace('adultiSelected', 'selected="selected"', $page);
      $page = str_replace('ragazziSelected', '', $page);
      $page = str_replace('dataSelected', '', $page);
      $page = str_replace('likeSelected', '', $page);
      $page = str_replace('alfabeticoSelected', '', $page);
      $replacement = createPresepeListAdu($connection);
    }
    if($_POST['selectFilter'] == 'ragazzi') {
      $page = str_replace('adultiSelected', '', $page);
      $page = str_replace('ragazziSelected', 'selected="selected"', $page);
      $page = str_replace('dataSelected', '', $page);
      $page = str_replace('likeSelected', '', $page);
      $page = str_replace('alfabeticoSelected', '', $page);
      $replacement = createPresepeListRaga($connection);
    }
    if($_POST['selectFilter'] == 'data') {
      $page = str_replace('adultiSelected', '', $page);
      $page = str_replace('ragazziSelected', '', $page);
      $page = str_replace('dataSelected', 'selected="selected"', $page);
      $page = str_replace('likeSelected', '', $page);
      $page = str_replace('alfabeticoSelected', '', $page);
      $replacement = createPresepeList($connection);
    }
    if($_POST['selectFilter'] == 'alfabetico') {
      $page = str_replace('adultiSelected', '', $page);
      $page = str_replace('ragazziSelected', '', $page);
      $page = str_replace('dataSelected', '', $page);
      $page = str_replace('likeSelected', '', $page);
      $page = str_replace('alfabeticoSelected', 'selected="selected"', $page);
      $replacement = createPresepeListAlph($connection);
    }
    if($_POST['selectFilter'] == 'like') {
      $page = str_replace('adultiSelected', '', $page);
      $page = str_replace('ragazziSelected', '', $page);
      $page = str_replace('dataSelected', '', $page);
      $page = str_replace('likeSelected', 'selected="selected"', $page);
      $page = str_replace('alfabeticoSelected', '', $page);
      $replacement = createPresepeListLike($connection);
    }
  }

  $page = str_replace('adultiSelected', '', $page);
  $page = str_replace('ragazziSelected', '', $page);
  $page = str_replace('dataSelected', '', $page);
  $page = str_replace('likeSelected', '', $page);
  $page = str_replace('alfabeticoSelected', '', $page);

  if(strlen($replacement) == 0 && !isset($_GET['search'])) {
    if(isset($_POST['selectFilter']) && $_POST['selectFilter'] != ''){
      if($_POST['selectFilter'] == 'adulti') {
        $replacement = '<li><p tabindex="0">Non è presente alcun presepe nella categoria Adulti!</p></li>';
      }
      if($_POST['selectFilter'] == 'ragazzi') {
        $replacement = '<li><p tabindex="0">Non è presente alcun presepe nella categoria Ragazzi!</p></li>';
      }
      if($_POST['selectFilter'] == 'like') {
        $replacement = '<li><p tabindex="0">Non è ancora stato lasciato alcun Mi Piace!</p></li>';
      }
    }
    else {
      $replacement = '<li><p tabindex="0">Non è ancora stato caricato alcun presepe!</p></li>';
    }
  }

  $connection->close();
  $page = str_replace('<placeholderLista />', $replacement, $page);
  echo($page);
?>