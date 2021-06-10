<?php
  function checkUserConnection() {
    if(isset($_SESSION["uName"]) && $_SESSION["loggedin"]=='admin') {
      return('<span class="logHint">Ciao <a href="<rootDIR></rootDIR>/php/admin.php" role="button">' . $_SESSION["uName"] . '</a> ! <a href="<rootDIR></rootDIR>/php/logout.php" role="button"> Effettua il logout </a></span>');
    }
    else if(isset($_SESSION["uName"]) && $_SESSION["loggedin"]=='users') {
      return('<span class="logHint">Ciao <a href="<rootDIR></rootDIR>/php/users.php" role="button">' . $_SESSION["uName"] . '</a> ! <a href="<rootDIR></rootDIR>/php/logout.php" role="button"> Effettua il logout </a></span>');
    }
    else {
      return ('<span class="logHint"> Ciao! Non sei collegato (<a href="<rootDIR></rootDIR>/php/login.php" lang="en"> Login </a> o <a href="<rootDIR></rootDIR>/php/register.php"> Registrati </a>)</span>');
    }
  }
?>