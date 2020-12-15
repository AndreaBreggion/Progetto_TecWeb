<?php
    /* Classe necessaria alla costruzione delle varie pagine */
    class TemplateBuilder {
        private $pageName;
        private $navbar;
        
        // costruttore con parametro: prende in input il nome della pagina
        // utile per la costruzione dell'header
        public function __construct($pageName) {
            $this->pageName = $pageName;
        }

        public function setHeader() {
            $this->header = file_get_contents(__DIR__."/content/_header.html");
            if($this->pageName=="homepage") {
                $this->header = str_replace("{title}", "Concorso Farra di Soligo", $this->header);
            }
        }

        public function setNavbar($_navbar) {
            $this->navbar = $_navbar;
        }
    }