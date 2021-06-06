<?php

session_start();
require_once("templateBuilder.php");
require_once('scripts/checkUserConnected.php');
require_once('scripts/connection.php');
require_once('scripts/statementQuery.php');

// il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
$builder = new TemplateBuilder( "/common/_pageTemplate", "..");
$builder->setHead(file_get_contents(__DIR__ . "/content/common/_head.html"));
$builder->setHeader(file_get_contents(__DIR__ . "/content/common/_header.html"), checkUserConnection());
$builder->setFooter(file_get_contents(__DIR__ . "/content/common/_footer.html"));
$builder->setBreadcrumb(file_get_contents(__DIR__ . "/content/common/_breadcrumbs.html"), array('<li class="current" aria-current="location"><span lang="en">Home</span></li>'));
$page = $builder->build();
$replacement= '<h1> 404 not found </h1>';
if(isset($_GET['presepeId'])) {
    $where = $_GET['presepeId'];
    $connection = connect();
    $query = 'SELECT * FROM presepi WHERE id = ?';
    $result = statementQuery($connection, $where, $query);
    $connection->close();
    if($result) {

    }
}
$page = str_replace('<placeholderContent></placeholderContent>', $replacement, $page);
echo($page);

?>