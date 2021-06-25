<?php
session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/connection.php');
require_once('scripts/statementQuery.php');

$builder = new TemplateBuilder("/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"), "register");
$builder->setDescription("Pagina di registrazione al sito dedicato al Concorso Presepi di Farra di Soligo");
$builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array('<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Registrati</span></li>'));
$page = $builder->build();
$page = str_replace('<a href="../php/register.php">Registrati</a>', ' <span>Registrati</span>', $page);
if(!isset($_SESSION['uId'])) {
  $page = str_replace('<placeholderContent />', file_get_contents(__DIR__."/content/common/_registerForm.html"), $page);
} else {
  $page = str_replace('<placeholderContent />', file_get_contents(__DIR__."/content/common/_errorAlreadyLogged.html"), $page);
}
$connection = connect();
if(isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $userNameFinalResult = '';
  if(empty($username)) $userNameFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
  if(strlen($username) < 3) $userNameFinalResult = '<p class="errorMsg" tabindex="0">Lo username non può avere meno di 3 caratteri</p>';
  if(strlen($username) > 10) $userNameFinalResult = '<p class="errorMsg" tabindex="0">Lo username non può avere più di dieci caratteri</p>';
  if(!preg_match('/^[a-zA-Z][a-zA-Z0-9.,$;]+$/', $username)) $userNameFinalResult = '<p class="errorMsg" tabindex="0">Lo username deve contenere solo caratteri alfanumerici e iniziare con una lettera</p>';
  $query = 'SELECT * FROM users WHERE username = ?';
  $isUserNameAlreadyTaken = statementQuery($connection, $username, $query);
  if($isUserNameAlreadyTaken) $userNameFinalResult = '<p class="errorMsg" tabindex="0">Questo username è già stato scelto!</p>';
  $mail = trim($_POST['mail']);
  $query = 'SELECT * FROM users WHERE mail = ?';
  $isMailAlreadyRegistered = statementQuery($connection, $mail, $query);
  $mailFinalResult = '';
  if($isMailAlreadyRegistered) $mailFinalResult = '<p class="errorMsg" tabindex="0">Questa mail è già stata registrata!</p>';
  if(empty($mail)) $mailFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
  if(!preg_match('/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $mail)) $mailFinalResult = '<p class="errorMsg" tabindex="0">Mail non valida!</p>';

  $password = trim($_POST['password']);
  $passwordFinalResult = '';
  if(empty($password)) $passwordFinalResult= '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
  if(strlen($password) < 4) $passwordFinalResult= '<p class="errorMsg" tabindex="0">La password deve contenere almeno 4 caratteri!</p>';
  if(strlen($password) > 64) $passwordFinalResult= '<p class="errorMsg" tabindex="0">La password non può avere più di 64 caratteri!</p>';
  if(empty($password)) $passwordFinalResult= '<p class="errorMsg" tabindex="0">La password non può essere vuota!</p>';

  $name = trim($_POST['name']);
  $nameFinalResult = '';
  if(empty($name)) $nameFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
  if(strlen($name) < 3) $nameFinalResult= '<p class="errorMsg" tabindex="0">Il nome deve avere almeno 3 caratteri</p>';
  if(strlen($name) > 24) $nameFinalResult= '<p class="errorMsg" tabindex="0">Il nome non deve avere più di 24 caratteri</p>';
  if(preg_match('/[0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]/', $name)) $nameFinalResult = '<p class="errorMsg" tabindex="0">Nome non valido!</p>';

  $surname = trim($_POST['surname']);
  $surnameFinalResult = '';
  if(empty($surname)) $surnameFinalResult = '<p class="errorMsg" tabindex="0">Campo obbligatorio</p>';
  if(strlen($surname) < 3) $surnameFinalResult= '<p class="errorMsg" tabindex="0">Il cognome deve avere almeno 3 caratteri</p>';
  if(strlen($surname) > 24) $surnameFinalResult= '<p class="errorMsg" tabindex="0">Il cognome non deve avere più di 24 caratteri</p>';
  if(preg_match('/[0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~\-]/', $surname)) $surnameFinalResult = '<p class="errorMsg" tabindex="0">Cognome non valido!</p>';

  $page = str_replace('<mailHint />', $mailFinalResult, $page);
  $page = str_replace('<usernameHint />', $userNameFinalResult, $page);
  $page = str_replace('<nameHint />', $nameFinalResult, $page);
  $page = str_replace('<surnameHint />', $surnameFinalResult, $page);
  $page = str_replace('<passwordHint />', $passwordFinalResult, $page);


  $res = trim($nameFinalResult . $mailFinalResult . $surnameFinalResult . $passwordFinalResult . $userNameFinalResult);
  if(strlen($res) === 0) {
    $page = str_replace('mailvaluePlaceholder', '', $page);
    $page = str_replace('usernameValuePlaceholder', '', $page);
    $page = str_replace('nameValuePlaceholder', '', $page);
    $page = str_replace('surNameValuePlaceholder', '', $page);
    $page = str_replace('passwordValuePlaceholder', '', $page);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (mail, name, surname, password, username) VALUES (?,?,?,?,?);";
    $stmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $mail , $name, $surname, $password, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $page = str_replace('<msgPlaceholder></msgPlaceholder>', '<p class="successMsg" tabindex="1"> La registrazione è andata a buon fine! <a href="login.php"> Effettua il login! </a></p>', $page);
  } else {
    $page = str_replace('mailvaluePlaceholder', htmlspecialchars($mail), $page);
    $page = str_replace('usernameValuePlaceholder', htmlspecialchars($username), $page);
    $page = str_replace('nameValuePlaceholder', htmlspecialchars($name), $page);
    $page = str_replace('surNameValuePlaceholder', htmlspecialchars($surname), $page);
    $page = str_replace('passwordValuePlaceholder', htmlspecialchars($password), $page);
    $page = str_replace('<msgPlaceholder></msgPlaceholder>', '<p class="errorMsg" tabindex="1"> La registrazione non è andata a buon fine, ricontrolla i campi. </p>', $page);
  }
} else {
  $page = str_replace('<mailHint />', '', $page);
  $page = str_replace('<usernameHint />', '', $page);
  $page = str_replace('<nameHint />', '', $page);
  $page = str_replace('<surnameHint />', '', $page);
  $page = str_replace('<passwordHint />', '', $page);
  $page = str_replace('<msgPlaceholder></msgPlaceholder>', '', $page);
  $page = str_replace('mailvaluePlaceholder', '', $page);
  $page = str_replace('usernameValuePlaceholder', '', $page);
  $page = str_replace('nameValuePlaceholder', '', $page);
  $page = str_replace('surNameValuePlaceholder', '', $page);
  $page = str_replace('passwordValuePlaceholder', '', $page);
}
$connection->close();
echo($page);
?>