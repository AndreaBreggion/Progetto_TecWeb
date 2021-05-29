<?php
    require_once("php/templateBuilder.php");
    
    // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
    $builder = new TemplateBuilder("home", ".");
    $builder->setHead(file_get_contents(__DIR__."/php/content/common/_head.html"));
    $builder->setHeader(file_get_contents(__DIR__."/php/content/common/_header.html"));
    $builder->setFooter(file_get_contents(__DIR__."/php/content/common/_footer.html"));
    $page = $builder->build();
    $page = str_replace('<li><a lang="en" href="./index.php">Home</a></li>', '<li class="current" aria-current="page"><a href="." lang="en">Home</a></li>', $page);
    echo($page);
?>