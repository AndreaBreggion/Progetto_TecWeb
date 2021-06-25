<?php
    /* Classe necessaria alla costruzione delle varie pagine */
    class TemplateBuilder {
        private $_institution = "Concorso Farra di Soligo";
        private $_pageName;
        private $_rootDIR;
        private $_whole_page;
        
        // costruttore con parametro
        public function __construct(string $pageName, string $rootDIR) {
            $this->_pageName = $pageName;
            $this->_rootDIR = $rootDIR;
            $this->_whole_page = file_get_contents(__DIR__ . "/content/" . $this->_pageName . ".html");
        }

        // setter per l'<head>
        public function setHead($head, string $name, string $otherInfo='') {
            if($name=="home") {
                $head = str_replace("<titlePH />", $this->_institution, $head);
            }
            else if($name=="presepiInGara") {
                if($otherInfo=="ricerca") {
                    $head = str_replace("<titlePH />", "Risultati Ricerca | " . $this->_institution, $head);
                } else {
                    $head = str_replace("<titlePH />", "Presepi in Gara | " . $this->_institution, $head);
                }
            }
            else if($name=="aggiungiPresepe") {
                $head = str_replace("<titlePH />", "Carica il tuo Presepe | " . $this->_institution, $head);
            }
            else if($name=="presepe") {
                $head = str_replace("<titlePH />", "Informazioni " . $otherInfo . " | " . $this->_institution, $head);
            }
            else if($name=="login") {
                $head = str_replace("<titlePH />", "Accedi | " . $this->_institution, $head);
            }
            else if($name=="register") {
                $head = str_replace("<titlePH />", "Registrati | " . $this->_institution, $head);
            }
            else if($name=="regole") {
                $head = str_replace("<titlePH />", "Regole del Concorso | " . $this->_institution, $head);
            }
            else if($name=="successo") {
                $head = str_replace("<titlePH />", "Conferma caricamento | " . $this->_institution, $head);
            }
            else if($name=="user") {
                $head = str_replace("<titlePH />", "Pagina personale " . $otherInfo . " | " . $this->_institution, $head);
            }
            else if($name=="vincitori") {
                $head = str_replace("<titlePH />", "Vincitori | " . $this->_institution, $head);
            }
            else if($name=="404") {
                $head = str_replace("<titlePH />", "404 | " . $this->_institution, $head);
            }
            // sostituisce il contenuto di <headPH /> (PlaceHolder) in $_whole_page
            // con quello di $head
            $this->_whole_page = str_replace("<headPH />", $head, $this->_whole_page);
        }

        public function setDescription(string $description) {
            $this->_whole_page = str_replace("<descriptionPH />", $description, $this->_whole_page);
        }

        // setter per l'header
        public function setHeader($header, $hint = null) {
            // sostituisce il contenuto di <headerPH /> (PlaceHolder) in $_whole_page
            // con quello di $header
            $this->_whole_page = str_replace("<headerPH />", $header, $this->_whole_page);
            if( $hint ) {
              $this->_whole_page = str_replace("<placeholderLog />", $hint, $this->_whole_page);
            } else {
              $this->_whole_page = str_replace("<placeholderLog />", '', $this->_whole_page);
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
          $breadcrumb = str_replace('<breadcrumbsPH />', $replacement, $breadcrumb);
          $this->_whole_page = str_replace("<placeholderBreadcrumbsPH />", $breadcrumb, $this->_whole_page);
        }
        
        // setter per il footer
        public function setFooter($footer) {
            // sostituisce il contenuto di <footerPH /> (PlaceHolder) in $_whole_page
            // con quello di $footer
            $this->_whole_page = str_replace("<footerPH />", $footer, $this->_whole_page);
        }

        // costruisce l'intera pagina
        public function build() {
            $this->_whole_page = str_replace("<rootDIR />", $this->_rootDIR, $this->_whole_page);
            return $this->_whole_page;
        }
    }
?>