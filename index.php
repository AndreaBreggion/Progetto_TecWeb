<?php
    require_once("php/templateBuilder.php");

    // il parametro in input deve avere lo stesso nome del file che contiene tutto il codice html
    $builder = new TemplateBuilder("home");
    $builder->setHead();
    
    $builder->setHeader();
    $builder->setBreadcrumb();
    $builder->setNavbar();
    $builder->setFooter();

    $builder->build();
?>