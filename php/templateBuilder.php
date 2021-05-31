<?php
    /* Classe necessaria alla costruzione delle varie pagine */
    class TemplateBuilder {
        private $_pageName;
        private $_rootDIR;
        private $_whole_page;
        
        // costruttore con parametro: prende in input il nome della pagina
        public function __construct(string $pageName, string $rootDIR) {
            $this->_pageName = $pageName;
            $this->_rootDIR = $rootDIR;
            $this->_whole_page = file_get_contents(__DIR__ . "/content/" . $this->_pageName . ".html");
        }

        // setter per l'<head>
        public function setHead($head) {
            // da creare un caso per ogni pagina
            if($this->_pageName=="home") {
                $head = str_replace("{title}", "Concorso Farra di Soligo", $head);
            }
            else if($this->_pageName=="presepiInGara") {
                $head = str_replace("{title}", "Presepi in Gara", $head);
            }
            else if($this->_pageName=="aggiungiPresepe") {
                $head = str_replace("{title}", "Carica il tuo Presepe", $head);
            }
            else if($this->_pageName=="edizioniPassate") {
                $head = str_replace("{title}", "Edizioni Passate", $head);
            }
            else if($this->_pageName=="articoli") {
                $head = str_replace("{title}", "Articoli", $head);
            }
            else if($this->_pageName=="vincitori") {
                $head = str_replace("{title}", "Vincitori", $head);
            }
            
            // sostituisce il contenuto di <headPH></headPH> (PlaceHolder) in $_whole_page
            // con quello di $head
            $this->_whole_page = str_replace("<headPH></headPH>", $head, $this->_whole_page);
        }

        // setter per l'header
        public function setHeader($header, $hint = null) {
            // sostituisce il contenuto di <headerPH></headerPH> (PlaceHolder) in $_whole_page
            // con quello di $header
            $this->_whole_page = str_replace("<headerPH></headerPH>", $header, $this->_whole_page);
            if( $hint ) {
              $this->_whole_page = str_replace("<placeholderLog></placeholderLog>", $hint, $this->_whole_page);
            } else {
              $this->_whole_page = str_replace("<placeholderLog></placeholderLog>", '', $this->_whole_page);
            }
        }
        
        // setter per il breadcrumb
        public function setBreadcrumb($breadcrumb, $linkArray) {
            // sostituisce il contenuto di <breadcrumbPH></breadcrumbPH> (PlaceHolder) in $_whole_page
            // con quello di $breadcrumb
          $replacement = '';
          foreach ($linkArray as $index => $link) {
            $replacement .= $link;
            if($index != count($linkArray) -1) $replacement .= '<li aria-hidden="true"><span>/</span></li>';
          }
          $breadcrumb = str_replace('<breadcrumbsPH></breadcrumbsPH>', $replacement, $breadcrumb);
          $this->_whole_page = str_replace("<placeholderBreadcrumbsPH></placeholderBreadcrumbsPH>", $breadcrumb, $this->_whole_page);
        }
        
        // setter per il footer
        public function setFooter($footer) {
            // sostituisce il contenuto di <footerPH></footerPH> (PlaceHolder) in $_whole_page
            // con quello di $footer
            $this->_whole_page = str_replace("<footerPH></footerPH>", $footer, $this->_whole_page);
        }



        // costruisce l'intera pagina
        public function build() {
            $this->_whole_page = str_replace("<rootDIR></rootDIR>", $this->_rootDIR, $this->_whole_page);
            return $this->_whole_page;
        }
    }
?>