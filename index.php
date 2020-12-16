<?php
    require_once("php/templateBuilder.php");

    session_start();

    $builder = new TemplateBuilder("homepage");

    $builder->setHeader();
    //$builder->setNavbar(file_get_contents(__DIR__."/php/content/_navbar.html"));
    $builder->setBody(file_get_contents(__DIR__."/php/content/home_body.html"));



?>