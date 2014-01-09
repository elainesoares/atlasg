<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of Valores
     *
     * @author Lorran
     */
    class ValorFiltro {
        private $id;
        private $nome;
        
        public function __construct($iId,$iNome) {
            $this->setValor($iId,$iNome);
        }
        
        private function setValor($iId,$iNome){
            $this->id = $iId;
            $this->nome = $iNome;
        }
        
        public function getId(){
            return $this->id;
        }
        
        public function getNome(){
            return $this->nome;
        }
        
        public function setId($id){
            $this->id = $id;
        }
    }

?>
