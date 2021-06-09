<?php
  function handleVisitedPages ($currentPage) {
    if (!isset($_SESSION['lastPages'])) {
      $lastPages = array();
      $lastPages[0] = null;
      $lastPages[1] = $currentPage;
      $_SESSION['lastPages'] = $lastPages;
    } else {
      $_SESSION['lastPages'][0] = $_SESSION['lastPages'][1];
      $_SESSION['lastPages'][1] = $currentPage;
    }
  }
?>