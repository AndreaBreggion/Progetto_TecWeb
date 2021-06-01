<?php
  session_start();
  require_once("../php/templateBuilder.php");

  // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
  $builder = new TemplateBuilder("aggiungiPresepe", "..");

  $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));
    
  $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"));
  $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));
  $builder->setBreadcrumb(file_get_contents(__DIR__."/content/common/_breadcrumbs.html"), array(
    '<li><a href="../index.php" lang="en">Home</a></li>',
    '<li class="current" aria-current="page"><span class="currentCrumb">Aggiungi presepe</span></li>'
  ));
  echo($builder->build());
?>