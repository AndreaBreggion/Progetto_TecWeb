<?php
  function checkUserConnection() {
    if(isset($_SESSION["uName"])) {
      return('<span class="logHint">Ciao  ' . $_SESSION["uName"].'! ' .'<a href="php/logout.php" role="button">  Effettua il logout</a></span>');
    } else {
      return ('<span class="logHint"> Ciao! Non sei collegato (<a href="<rootDIR></rootDIR>/php/login.php" lang="en"> Login </a> o <a href="<rootDIR></rootDIR>/php/registrati.php"> Registrati </a>)</span>');
    }
  }
?>