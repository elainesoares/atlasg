<?php
    /**
     * Description of EstruturaEtaria
     *
     * @author Lorran
     */
    class LonMorFec extends Texto {

        private $texto = "A mortalidade infantil (crianças com menos de um ano) 
            em [Município] [mortalinfantil_diminuiu/aumentou] 
            [reducao_mortalinfantil0010]%, passando de [mortinfantil00] por mil 
            nascidos vivos em 2000 para [mortinfantil10] por mil nascidos vivos 
            em 2010. Segundo a Meta do Milênio das Nações Unidas,  a mortalidade 
            infantil para o Brasil deve estar abaixo de 17,9 óbitos por mil em 
            2015. Em 2010, as taxas de mortalidade infantil do estado e do país 
            eram [mortinfantil10_Estado] e [mortinfantil10_Brasil] por mil 
            nascidos vivos, respectivamente.";

        public function __construct($municipio, $idmunicipio) {
            parent::__construct(&$this->texto,8);
            
            
            $this->setData("subtitulo", "LONGEVIDADE, MORTALIDADE E FECUNDIDADE");
            $this->setData("text", $this->pTexto);
        }

        public function getTexto(){
            return parent::getTexto();
        }
        
        public function __destruct() {
            
        }

    }

?>
