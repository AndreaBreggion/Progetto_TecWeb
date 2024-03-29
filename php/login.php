<?php
  session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/connection.php');
  require_once('scripts/statementQuery.php');
  require_once('scripts/checkUserConnected.php');

  $builder = new TemplateBuilder("login", "..");
  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "login");
  $builder->setDescription("Pagina di accesso, come utente registrato, al sito dedicato al Concorso Presepi di Farra di Soligo");
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb" lang="en">Login</span></li>'
  ));
  $page = $builder->build();
  $page = str_replace('<a href="../php/login.php" lang="en">Login</a>', ' <span>Login</span>', $page);
  $connection = connect();
  $replacement = '';
  if(isset($_POST['submit'])) {
    $result = '';
    $query = 'SELECT * FROM users WHERE username = ?';
    $data = statementQuery($connection, $_POST['username'], $query);
    if($data) {
      $passwordCheck = password_verify($_POST['password'], $data['password']);
      if(!$passwordCheck){
        $result = false;
      } else {
        $_SESSION["uId"] = $data["id"];
        $_SESSION["uName"] = $data["username"];
        $_SESSION["uRealName"] = $data["name"];
        $_SESSION["uSurname"] = $data["surname"];
        $_SESSION["uMail"] = $data["mail"];
        if($data["admin"]) {
          $_SESSION["loggedin"] = 'admin';
        } else {
          $_SESSION["loggedin"] = 'users';
        }
        $result = true;
      }
    } else {
      $result = false;
    }
    if($result) {
      $page = str_replace('<p class="registerHint"> Non ti sei ancora registrato? <a href="../php/register.php"> Registrati </a></p>', '', $page);
      $replacement = '<span> Login avvenuto correttamente! benvenuto ' .$_SESSION["uName"] .'!</span>';
      header('location: '.$_SESSION['lastPages'][1]);
    } else {
      $page = str_replace('<input type="text" id="username" class="formInput" name="username" placeholder="Username" required>', '<input type="text" id="username" class="formInput" name="username" placeholder="Username" value="'.htmlspecialchars($_POST['username']).'" required>', $page);
      $replacement = '<span class="errorMsg" tabindex="1">Login non corretto</span>';
    }
  }
  $page = str_replace('<loginMsgPlaceholder></loginMsgPlaceholder>', $replacement, $page);
  echo($page);
?>
