<?php
session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/connection.php');
require_once('scripts/statementQuery.php');

// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder("/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));

$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array('<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Registrati</span></li>'));
$page = $builder->build();

if(!isset($_SESSION['uId'])) {
  $page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__."/content/common/_registerForm.html"), $page);
} else {
  $page = str_replace('<placeholderContent></placeholderContent>', file_get_contents(__DIR__."/content/common/_errorAlreadyLogged.html"), $page);
}
$connection = connect();
if(isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $userNameFinalResult = '';
  if(empty($username)) $userNameFinalResult = '<p>Campo obbligatorio</p>';
  if(strlen($username) < 3) $userNameFinalResult = '<p>lo username non può avere meno di 3 caratteri</p>';
  if(strlen($username) > 10) $userNameFinalResult = '<p>lo username non può avere più di dieci caratteri</p>';
  if(!preg_match('/^[a-zA-Z][a-zA-Z0-9.,$;]+$/', $username)) $userNameFinalResult = '<p>lo username deve contenere solo caratteri alfanumerici e iniziare con una lettera</p>';
  $query = 'SELECT * FROM user WHERE username = ?';
  $isUserNameAlreadyTaken = statementQuery($connection, $username, $query);
  if($isUserNameAlreadyTaken) $userNameFinalResult = '<p>Questo username è già stato scelto!</p>';
  $mail = trim($_POST['mail']);
  $query = 'SELECT * FROM user WHERE mail = ?';
  $isMailAlreadyRegistered = statementQuery($connection, $mail, $query);
  $mailFinalResult = '';
  if($isMailAlreadyRegistered) $mailFinalResult = '<p>Questa mail è già stata registrata!</p>';
  if(empty($mail)) $mailFinalResult = '<p>Campo obbligatorio</p>';
  if(!preg_match('/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $mail)) $mailFinalResult = '<p>Mail non valida!</p>';

  $password = trim($_POST['password']);
  $passwordFinalResult = '';
  if(empty($password)) $passwordFinalResult= '<p>Campo obbligatorio</p>';
  if(strlen($password) < 6) $passwordFinalResult= '<p>La password deve contenere almeno 6 caratteri!</p>';
  if(strlen($password) > 64) $passwordFinalResult= '<p>La password non può avere più di 64 caratteri!</p>';
  if(empty($password)) $passwordFinalResult= '<p>La password non può essere vuota!</p>';

  $name = trim($_POST['name']);
  $nameFinalResult = '';
  if(empty($name)) $nameFinalResult = '<p>Campo obbligatorio</p>';
  if(strlen($name) < 3) $nameFinalResult= '<p>Il nome deve avere almeno 3 caratteri</p>';
  if(strlen($name) > 24) $nameFinalResult= '<p>Il nome non deve avere più di 24 caratteri</p>';
  if(preg_match('/[0-9!#$%&\'*+/=?^_\`{|}~-]/', $name)) $nameFinalResult = '<p>Nome non valido!</p>';

  $surname = trim($_POST['surname']);
  $surnameFinalResult = '';
  if(empty($surname)) $surnameFinalResult = '<p>Campo obbligatorio</p>';
  if(strlen($surname) < 3) $surnameFinalResult= '<p>Il cognome deve avere almeno 3 caratteri</p>';
  if(strlen($surname) > 24) $surnameFinalResult= '<p>Il cognome non deve avere più di 24 caratteri</p>';
  if(preg_match('/[0-9!#$%&\'*+/=?^_\`{|}~-]/', $surname)) $surnameFinalResult = '<p>Cognome non valido!</p>';

  $page = str_replace('<mailHint />', $mailFinalResult, $page);
  $page = str_replace('<usernameHint />', $userNameFinalResult, $page);
  $page = str_replace('<nameHint />', $nameFinalResult, $page);
  $page = str_replace('<surnameHint />', $surnameFinalResult, $page);
  $page = str_replace('<passwordHint />', $passwordFinalResult, $page);

  $res = trim($nameFinalResult . $mailFinalResult . $surnameFinalResult . $passwordFinalResult . $userNameFinalResult);
  if(strlen($res) === 0) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (mail, name, surname, password, username) VALUES (?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $mail , $name, $surname, $password, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
$connection->close();
echo($page);
?>