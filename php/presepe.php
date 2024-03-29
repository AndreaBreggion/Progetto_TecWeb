<?php

session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/connection.php');
require_once('scripts/statementQuery.php');
require_once('scripts/hasUserLiked.php');
require_once('scripts/lastVisitedPages.php');
require_once('scripts/hasAdminSelected.php');

$builder = new TemplateBuilder( "/common/_pageTemplate", "..");

if(isset($_GET['presepeId'])) {
  $where = $_GET['presepeId'];
  $connection = connect();
  $query = 'SELECT presepi.*, users.username as username, users.id as UID FROM presepi JOIN users ON presepi.uId = users.id AND presepi.id = ?';
      $result = statementQuery($connection, $where, $query);
  if($result) {
    $builder->setHead(file_get_contents(__DIR__ . "/content/common/_head.html"), "presepe", $result['presepeName']);
    $builder->setDescription("Pagina che illustra i dettagli del presepe " . $result['presepeName'] . " e permette di interagirvi");
  } else {
    $builder->setHead(file_get_contents(__DIR__ . "/content/common/_head.html"), "presepe", "presepe");
    $builder->setDescription("Pagina che illustra i dettagli del presepe e permette di interagirvi");
  }
  $connection->close();
}


$builder->setHeader(file_get_contents(__DIR__ . "/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__ . "/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__ . "/content/common/_breadcrumbs.html"), array('<li><a href="../index.php" lang="en">Home</a></li>',
  '<li><a href="./presepiInGara.php">Presepi in gara</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb"><presepeBreadcrumbPlaceholder /></span></li>'));
$page = $builder->build();
$replacement= '<h2 tabindex="1">Errore 404: presepe non trovato!</h2>';
if(isset($_GET['presepeId'])) {
    handleVisitedPages('./presepe.php?presepeId='.$_GET['presepeId']);
    $where = $_GET['presepeId'];
    $_SESSION['lastVisitedPresepe'] = $_GET['presepeId'];
    $connection = connect();
    $query = 'SELECT presepi.*, users.username as username, users.id as UID FROM presepi JOIN users ON presepi.uId = users.id AND presepi.id = ?';
    $result = statementQuery($connection, $where, $query);
    $query = 'SELECT COUNT(*) FROM likes WHERE pId = ?';
    $likeNumber = statementQuery($connection, $where, $query);
    $hasUserLikedPresepe = isset($_SESSION['uId']) ? hasUserLiked($connection, $_SESSION['uId'], $_GET['presepeId']) : null;
    $hasAdminSelected = isset($_SESSION['uId']) ? hasAdminSelected($connection, $_GET['presepeId']) : null;
    $form = isset($_SESSION['uId']) ? file_get_contents(__DIR__ .'/content/common/_presepeCommentForm.html') : '';
    $query = 'SELECT comments.uId as uId, comments.comment as comment, comments.timestamp as timestamp, users.username as username from comments join users ON comments.uId = users.id WHERE comments.pId = ? ORDER BY timestamp DESC';
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, 's', $where);
    mysqli_stmt_execute($stmt);
    $comments = mysqli_stmt_get_result($stmt);
    $commentThread = '';
    mysqli_stmt_close($stmt);
    $connection->close();
    if($result) {
      if(isset($_SESSION['uId'])) {
        $cancelPresepe = $_SESSION['uId'] == $result['UID'] || $_SESSION["loggedin"] == 'admin' ? file_get_contents(__DIR__ . "/content/common/_deletePresepeForm.html") : '';
        $winnerPresepe = $_SESSION["loggedin"] == 'admin' ? file_get_contents(__DIR__ . "/content/common/_setWinnerForm.html") : '';
        $likePresepe = file_get_contents(__DIR__ . "/content/common/_addLikeForm.html");
        $avvisoMiPiace = '';
        $avvisoCommenti = '';
      } else {
        $cancelPresepe = '';
        $winnerPresepe = '';
        $likePresepe = '';
        $avvisoMiPiace = '<p><a href="./login.php">Accedi</a> o <a href="./register.php">Registrati</a> per mettere il tuo <span lang="en">like</span> al presepe!</p>';
        $avvisoCommenti = '<p><a href="./login.php">Accedi</a> o <a href="./register.php">Registrati</a> per lasciare un commento!</p>';
      }
      $page = str_replace('<presepeBreadcrumbPlaceholder />', $result['presepeName'], $page);
      $replacement = file_get_contents(__DIR__ . "/content/common/_presepePage.html");
      $replacement = str_replace('<presepeCancelPlaceholder />', $cancelPresepe, $replacement);
      $replacement = str_replace('<placeholderImage />', $result['photoPath'], $replacement);
      $replacement = str_replace('<placeholderAlt />', 'Foto del presepe ' . $result['presepeName'], $replacement);
      $longdesc = str_replace('.', '', $result['photoPath']);
      $replacement = str_replace('<placeholderLongdesc />', $longdesc . 'longdesc' , $replacement);
      $replacement = str_replace('<placeholderIdLongdesc />', $longdesc . 'longdesc' , $replacement);
      $replacement = str_replace('<titlePH />', $result['presepeName'], $replacement);
      $replacement = str_replace('<placeholderAuthor />', $result['username'], $replacement);
      $replacement = str_replace('<placeholderDate />', $result['dateOfCreation'], $replacement);
      $replacement = str_replace('<placeholderCategory />', $result['category'], $replacement);
      $replacement = str_replace('<placeholderDescription />', $result['description'], $replacement);
      $replacement = str_replace('<placeholderMiPiace />', $avvisoMiPiace, $replacement);
      $replacement = str_replace('<placeholderCommenti />', $avvisoCommenti, $replacement);
      $replacement = str_replace('<placeholderLikeNumber />', $likeNumber['COUNT(*)'], $replacement);
      $replacement = str_replace('<placeholderLike />', $likePresepe, $replacement);
      $replacement = str_replace('<placeholderVincitore />', $winnerPresepe, $replacement);
      $replacement = str_replace('<presepeFormPlaceholder />', $form, $replacement);
      if($hasUserLikedPresepe) $replacement = str_replace('<button class="presepeButton likeButton" aria-label="Mi piace!" type="submit" name="like">Mi piace!</button>', '<button class="presepeButton likeButton" aria-label="Non mi piace più!" type="submit" name="like">Non mi piace più!</button>', $replacement);
      if($hasAdminSelected) $replacement = str_replace('<button class="presepeButton" type="submit" name="segna vincitore" aria-label="Segna come Vincitore">Segna come Vincitore</button>', '<button class="presepeButton" aria-label="Rimuovi dai Vincitori" type="submit" name="like">Rimuovi dai Vincitori</button>', $replacement);

      for($i = 1; $row = mysqli_fetch_assoc($comments); $i++) {
        if(isset($_SESSION['uId'])) {
          $cancelComment = ($_SESSION['uId'] == $row['uId']) || ($_SESSION['loggedin'] == 'admin') ? file_get_contents(__DIR__ . "/content/common/_deleteCommentForm.html") : '';
        } else {
          $cancelComment = '';
        }
        $commentTemplate = file_get_contents(__DIR__.'/content/common/_commentTemplate.html');
        $commentTemplate = str_replace('<placeholderCommentDeleteForm />', $cancelComment, $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentUsername />', $row['username'], $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentUId />', $row['uId'], $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentoDatatime />', $row['timestamp'], $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentProper />', $row['comment'], $commentTemplate);
        $commentThread .= $commentTemplate;
      }
      $replacement = $commentThread=='' ? str_replace('<presepeCommentThreadPlaceholder />', '<li><p>Non è presente alcun commento. Sii il primo a commentare!</p></li>', $replacement) : str_replace('<presepeCommentThreadPlaceholder />', $commentThread, $replacement);
    } else $page = str_replace('<presepeBreadcrumbPlaceholder />', 'Presepe non trovato', $page);
} else $page = str_replace('<presepeBreadcrumbPlaceholder />', 'Presepe non trovato', $page);
$page = str_replace('<placeholderContent />', $replacement, $page);
echo($page);

?>