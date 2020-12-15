<?php
    require_once("php/templateBuilder.php");

    $builder = new TemplateBuilder("homepage");

    $builder->setHeader();
    $builder->setNavbar(file_get_contents(__DIR__."/php/content/_navbar.html"));




?>