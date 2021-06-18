<?php

session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/connection.php');
require_once('scripts/statementQuery.php');
require_once('scripts/hasUserLiked.php');
require_once('scripts/lastVisitedPages.php');
require_once('scripts/hasAdminSelected.php');

// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder( "/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__ . "/content/common/_head.html"));
$builder->setHeader(file_get_contents(__DIR__ . "/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__ . "/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__ . "/content/common/_breadcrumbs.html"), array('<li><a href="../index.php" lang="en">Home</a></li>',
  '<li><a href="./presepiInGara.php">Presepi in gara</a></li>',
  '<li class="current" aria-current="page"><span class="currentCrumb"><presepeBreadcrumbPlaceholder /></span></li>'));
$page = $builder->build();
$replacement= '<h1> 404 not found </h1>';
if(isset($_GET['presepeId'])) {
    handleVisitedPages('/php/presepe.php?presepeId='.$_GET['presepeId']);
    $where = $_GET['presepeId'];
    $_SESSION['lastVisitedPresepe'] = $_GET['presepeId'];
    $connection = connect();
    $query = 'SELECT presepi.*, users.username as username, users.id as UID FROM presepi JOIN users ON presepi.uId = users.id AND presepi.id = ?';
    $result = statementQuery($connection, $where, $query);
    $query = 'SELECT COUNT(*) FROM likes WHERE pId = ?';
    $likeNumber = statementQuery($connection, $where, $query);
    $hasUserLikedPresepe = isset($_SESSION['uId']) ? hasUserLiked($connection, $_SESSION['uId'], $_GET['presepeId']) : null;
    $hasAdminSelected = isset($_SESSION['uId']) ? hasAdminSelected($connection, $_SESSION['uId'], $_GET['presepeId']) : null;
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
      $cancelPresepe = $_SESSION['uId'] == $result['UID'] || $_SESSION["loggedin"] == 'admin' ? file_get_contents(__DIR__ . "/content/common/_deletePresepeForm.html") : '';
      $winnerPresepe = $_SESSION["loggedin"] == 'admin' ? file_get_contents(__DIR__ . "/content/common/_setWinnerForm.html") : '';

      $page = str_replace('<presepeBreadcrumbPlaceholder />', $result['presepeName'], $page);
      $replacement = file_get_contents(__DIR__ . "/content/common/_presepePage.html");
      $replacement = str_replace('<presepeCancelPlaceholder />', $cancelPresepe, $replacement);
      $replacement = str_replace('<placeholderImage />', $result['photoPath'], $replacement);
      $replacement = str_replace('<placeholderTitle />', $result['presepeName'], $replacement);
      $replacement = str_replace('<placeholderAuthor />', $result['username'], $replacement);
      $replacement = str_replace('<placeholderDate />', $result['dateOfCreation'], $replacement);
      $replacement = str_replace('<placeholderCategory />', $result['category'], $replacement);
      $replacement = str_replace('<placeholderDescription />', $result['description'], $replacement);
      $replacement = str_replace('<placeholderLikeNumber />', $likeNumber['COUNT(*)'], $replacement);
      $replacement = str_replace('<placeholderVincitore />', $winnerPresepe, $replacement);
      $replacement = str_replace('<presepeFormPlaceholder />', $form, $replacement);
      if($hasUserLikedPresepe) $replacement = str_replace('<button class="likeButton" aria-label="Mi piace presepe" type="submit" name="like">Mi piace!</button>', '<button class="likeButton" aria-label="Rimuovi Mi piace presepe" type="submit" name="like">Non mi piace più!</button>', $replacement);
      if($hasAdminSelected) $replacement = str_replace('<button class="likeButton" aria-label="Aggiungi a vincitori" type="submit" name="like">Aggiungi a vincitori</button>', '<button class="likeButton" aria-label="Rimuovi vincitore" type="submit" name="like">Togli vincitore</button>', $replacement);

      for($i = 1; $row = mysqli_fetch_assoc($comments); $i++) {
        $cancelComment = $_SESSION['uId'] == $row['uId'] ? file_get_contents(__DIR__ . "/content/common/_deleteCommentForm.html") : '';
        $commentTemplate = file_get_contents(__DIR__.'/content/common/_commentTemplate.html');
        $commentTemplate = str_replace('<placeholderCommentDeleteForm />', $cancelComment, $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentNumber />', $i, $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentUsername />', $row['username'], $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentoDatatime />', $row['timestamp'], $commentTemplate);
        $commentTemplate = str_replace('<placeholderCommentProper />', $row['comment'], $commentTemplate);
        $commentThread .= $commentTemplate;
      }
      $replacement = str_replace('<presepeCommentThreadPlaceholder />', $commentThread, $replacement);
    } else $page = str_replace('<presepeBreadcrumbPlaceholder />', 'Presepe non trovato', $page);
} else $page = str_replace('<presepeBreadcrumbPlaceholder />', 'Presepe non trovato', $page);
$page = str_replace('<placeholderContent></placeholderContent>', $replacement, $page);
echo($page);

?>