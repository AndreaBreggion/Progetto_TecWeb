<?php
    /* Classe necessaria alla costruzione delle varie pagine */
    class TemplateBuilder {
        private $pageName;
        private $navbar;
        private $body;
        
        // costruttore con parametro: prende in input il nome della pagina
        // utile per la costruzione dell'header
        public function __construct(string $_pageName) {
            $this->pageName = $_pageName;
        }

        // setter per l'header
        public function setHeader() {
            $this->header = file_get_contents(__DIR__."/content/_header.html");
            if($this->pageName=="homepage") {
                $this->header = str_replace("{title}", "Concorso Farra di Soligo", $this->header);
            }
        }

        // setter per la navbar/menu
        public function setNavbar(string $_navbar) {
            $this->navbar = $_navbar;
        }

        // setter per il body
        public function setBody(string $_body) {
            $this->body = $_body;
        }
    }
?>