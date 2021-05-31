<?php
  function checkUserConnection() {
    if(isset($_SESSION['uId'])) {
      return('<span class="logHint"><a href="logout" role="button">Ciao ' . $_SESSION["usname"] . '! Effettua il logout</a></span>');
    }
    return('<span class="logHint"> Ciao! Non sei collegato (<a href="<rootDIR></rootDIR>/php/login.php" lang="en"> Login </a> o <a href="<rootDIR></rootDIR>/php/registrati.php"> Registrati </a>)</span>');
  }
?>