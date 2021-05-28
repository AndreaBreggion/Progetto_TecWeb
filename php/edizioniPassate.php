<?php
    require_once("../php/templateBuilder.php");

    // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
    $builder = new TemplateBuilder("edizioniPassate", "..");

    $builder->setHead(file_get_contents(__DIR__."/content/common/_head.html"));
    
    $builder->setHeader(file_get_contents(__DIR__."/content/common/_header.html"));
    $builder->setFooter(file_get_contents(__DIR__."/content/common/_footer.html"));

    $builder->build();
?>