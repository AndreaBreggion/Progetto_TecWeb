<?php
  session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/checkUserConnected.php');
  require_once('scripts/connection.php');
  require_once('scripts/statementQuery.php');
  require_once('scripts/lastVisitedPages.php');
  handleVisitedPages('/php/aggiungiPresepe.php');
  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("/common/_pageTemplate", "..");

  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "aggiungiPresepe");
    
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Aggiungi presepe</span></li>'
  ));
  $page = $builder->build();
  $page = str_replace('<li><a href="../php/aggiungiPresepe.php">Aggiungi Presepe</a></li>', '<li class="current" aria-current="page"><span class="currentPage">Aggiungi Presepe</span></li>', $page);

  if(!isset($_SESSION['uId'])) {
    header('location: login.php');
  } else {
    $page = str_replace('<placeholderContent />', file_get_contents(__DIR__.'/content/common/_addPresepeForm.html'), $page);
  }

  if(isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $titleFinalResult = '';
    if(empty($title)) $titleFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
    if(strlen($title) < 3) $titleFinalResult = '<p class="errorMsg" tabindex="0">il titolo non può avere meno di 3 caratteri</p>';
    if(strlen($title) > 48) $titleFinalResult = '<p class="errorMsg" tabindex="0">il titolo non può avere più di 48 caratteri</p>';
    if(preg_match('/[^a-zA-Z\d\s\.\!\?\,\;\:\è\È\à\ò\ù\é]/', $title)) $titleFinalResult = '<p class="errorMsg" tabindex="0">il titolo può contenere solo caratteri alfanumerici, spazi e punteggiatura</p>';

    $description = trim($_POST['description']);
    $descFinalResult = '';
    if(empty($description)) $descFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
    if(preg_match('/[^a-zA-Z\d\s\.\!\?\,\;\:\è\È\à\ò\ù\é]/', $description)) $descFinalResult = '<p class="errorMsg" tabindex="0">La descrizione può contenere solo caratteri alfanumerici, spazi e punteggiatura</p>';

    $category = $_POST['selectCategory'];
    $categoryFinalResult = '';
    if(empty($category)) $categoryFinalResult= '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';

    $fileFinalResult = '';
    if(!isset($_FILES['presepeImage']['name'])) $fileFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
    if($_FILES['presepeImage']['size'] > 10000000) $fileFinalResult = '<p class="errorMsg" tabindex="0">Dimensione del file troppo grande, dimensione massima 10mb</p>';

    $page = str_replace('<titleHint />', $titleFinalResult, $page);
    $page = str_replace('<descrizioneHint />', $descFinalResult, $page);
    $page = str_replace('<selectHint />', $categoryFinalResult, $page);
    $page = str_replace('<imageHint />', $fileFinalResult, $page);

    $finalResult = trim($titleFinalResult. $descFinalResult. $categoryFinalResult. $fileFinalResult);
    $connection = connect();
    $number = $connection->query('SELECT COUNT(presepeName) FROM presepi');
    $number = mysqli_fetch_row($number)[0];
    $imageName = $_SESSION['uId']. '_'. $number . '.' . pathinfo($_FILES['presepeImage']['name'], PATHINFO_EXTENSION);
    if(empty($finalResult)) $isImageSaved = move_uploaded_file($_FILES['presepeImage']['tmp_name'], __DIR__.'/../sources/images/'.$imageName);
    $date = date('Y-m-d');
    if(empty($finalResult) && $isImageSaved) {
      $page = str_replace('titlePlaceholder', '', $page);
      $page = str_replace('<descriptionPlaceholder />', '', $page);
      $page = str_replace('adultiSelected', '', $page);
      $page = str_replace('ragazziSelected', '', $page);
      $page = str_replace('<msgPlaceholder></msgPlaceholder>', '<p class="successMsg" tabindex="1"> Il tuo presepe è stato caricato!</p>', $page);
      $query = "INSERT INTO presepi (uId, photoPath, presepeName, category, description, dateOfCreation) VALUES (?,?,?,?,?,?);";
      $stmt = mysqli_stmt_init($connection);
      mysqli_stmt_prepare($stmt, $query);
      mysqli_stmt_bind_param($stmt, "isssss", $_SESSION['uId'] , $imageName, $title, $category, $description, $date);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      header('location: successPage.php');
    } else {
      $page = str_replace('titlePlaceholder', htmlspecialchars($title), $page);
      $page = str_replace('<descriptionPlaceholder />', $description, $page);
      $page = $category == 'adulti' ? str_replace('adultiSelected', 'selected', $page) : str_replace('adultiSelected', '', $page);
      $page = $category == 'ragazzi' ? str_replace('ragazziSelected', 'selected', $page) : str_replace('ragazziSelected', '', $page);
      $page = $isImageSaved ? str_replace('<msgPlaceholder></msgPlaceholder>', '<p class="errorMsg" tabindex="1"> L\'operazione non è andata a buon fine, ricontrolla i campi </p>', $page) : str_replace('<msgPlaceholder></msgPlaceholder>', '<p class="errorMsg" tabindex="1"> Errore durante il caricamento dell\'immagine </p>', $page);
    }
  } else {
    $page = str_replace('titlePlaceholder', '', $page);
    $page = str_replace('<descriptionPlaceholder />', '', $page);
    $page = str_replace('adultiSelected', '', $page);
    $page = str_replace('ragazziSelected', '', $page);
    $page = str_replace('<titleHint />', '', $page);
    $page = str_replace('<descrizioneHint />', '', $page);
    $page = str_replace('<selectHint />', '', $page);
    $page = str_replace('<imageHint />', '', $page);
  }
  echo($page);
?>