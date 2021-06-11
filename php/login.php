<?php
  session_start();
  require_once("../php/templateBuilder.php");
  require_once('scripts/connection.php');
  require_once('scripts/statementQuery.php');
  require_once('scripts/checkUserConnected.php');

  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("login", "..");

  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb" lang="en">Login</span></li>'
  ));

  $page = $builder->build();
  $connection = connect();
  $replacement = '';
  if(isset($_POST['submit'])) {
    $result = '';
    $query = 'SELECT * FROM users WHERE mail = ?';
    $data = statementQuery($connection, $_POST['mail'], $query);
    if($data) {
      $passwordCheck = password_verify($_POST['password'], $data['password']);
      if(!$passwordCheck){
        $result = false;
      } else {
        // session_start();
        $_SESSION["uId"] = $data["id"];
        $_SESSION["uName"] = $data["username"];
        $result = true;
      }
    } else {
      $result = false;
    }
    if($result) {
      $page = str_replace('<span class="registerHint"> Non ti sei ancora registrato? <a href="../php/register.php"> Registrati </a></span>', '', $page);
      $replacement = '<span> Login avvenuto correttamente! benvenuto ' .$_SESSION["uName"] .'!</span>';
      header('location: '.$_SESSION['lastPages'][1]);
    } else {
      $page = str_replace('<input type="text" id="mail" class="formInput" name="mail" placeholder="Mail" required />', '<input type="text" id="mail" class="formInput" name="mail" placeholder="Mail" value="'.htmlspecialchars($_POST['mail']).'" required />', $page);
      $replacement = '<span> Login non corretto </span>';
    }
  }
  $page = str_replace(' <loginMsgPlaceholder></loginMsgPlaceholder>', $replacement, $page);
  echo($page);
?>
