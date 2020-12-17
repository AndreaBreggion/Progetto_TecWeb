<?php
    /* Classe necessaria alla costruzione delle varie pagine */
    class TemplateBuilder {
        private $_head;
        private $_header;
        private $_breadcrumb;
        private $_navbar;
        private $_footer;
        
        private $_pageName;
        private $_whole_page;

        
        // costruttore con parametro: prende in input il nome della pagina
        public function __construct(string $pageName) {
            $this->_pageName = $pageName;
            $this->_whole_page = file_get_contents(__DIR__ . "/content/" . $this->_pageName . ".html");
        }

        // setter per l'<head>
        public function setHead() {
            // recupera il contenuto dell'head (generico)
            $this->_head = file_get_contents(__DIR__."/content/common/_head.html");
            
            // ne cambia il titolo
            // da creare un caso per ogni pagina
            if($this->_pageName=="home") {
                $this->_head = str_replace("{title}", "Concorso Farra di Soligo", $this->_head);
            }




            
            // sostituisce il contenuto di <headPH></headPH> (PlaceHolder) in $_whole_page
            // con quello di $this->_head
            $this->_whole_page = str_replace("<headPH></headPH>", $this->_head, $this->_whole_page);
        }

        // setter per l'header
        public function setHeader() {
            // recupera il contenuto dell'header (generico)
            $this->_header = file_get_contents(__DIR__."/content/common/_header.html");
            // sostituisce il contenuto di <headerPH></headerPH> (PlaceHolder) in $_whole_page
            // con quello di $this->_header
            $this->_whole_page = str_replace("<headerPH></headerPH>", $this->_header, $this->_whole_page);
        }
        
        // setter per il breadcrumb
        public function setBreadcrumb() {
            // recupera il contenuto del breadcrumb (generico, anche se bisognerà fare in modo che
            // cambi in automatico in base alla pagina)
            $this->_breadcrumb = file_get_contents(__DIR__."/content/common/_breadcrumb.html");
            // sostituisce il contenuto di <breadcrumbPH></breadcrumbPH> (PlaceHolder) in $_whole_page
            // con quello di $this->_breadcrumb
            $this->_whole_page = str_replace("<breadcrumbPH></breadcrumbPH>", $this->_breadcrumb, $this->_whole_page);
        }
        
        // setter per la navbar/menu
        public function setNavbar() {
            // recupera il contenuto della navbar (generico, anche se bisognerà fare in modo che
            //si disattivi in automatico il link alla pagina corrente)
            $this->_navbar = file_get_contents(__DIR__."/content/common/_navbar.html");
            // sostituisce il contenuto di <navbarPH></navbarPH> (PlaceHolder) in $_whole_page
            // con quello di $this->_navbar
            $this->_whole_page = str_replace("<navbarPH></navbarPH>", $this->_navbar, $this->_whole_page);
        }

        // setter per il footer
        public function setFooter() {
            // recupera il contenuto del footer (generico)
            $this->_footer = file_get_contents(__DIR__."/content/common/_footer.html");
            // sostituisce il contenuto di <footerPH></footerPH> (PlaceHolder) in $_whole_page
            // con quello di $this->_footer
            $this->_whole_page = str_replace("<footerPH></footerPH>", $this->_footer, $this->_whole_page);
        }

        // costruisce l'intera pagina
        public function build() {
            echo $this->_whole_page;
        }
    }
?>