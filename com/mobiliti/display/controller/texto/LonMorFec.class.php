<?php
    /**
     * Description of EstruturaEtaria
     *
     * @author Lorran
     */
    class EstruturaEtaria extends Texto {

        private $texto = "Entre 2000 e 2010, a razão de dependência (população de 
                          menos de 15 anos e de 65 anos ou mais em relação à 
                          população de 15 a 64 anos) de [municipio] passou de 
                          [rz_dependencia00]% para [rz_dependencia10]%. O índice 
                          de envelhecimento (população de 65 anos ou mais em relação 
                          à população de menos de 15 anos) evoluiu de 
                          [indice_envelhecimento00]% para [indice_envelhecimento10]%. 
                          Entre 1991 e 2000, a razão de dependência foi de 
                          [rz_dependencia91]% para [rz_dependencia00]%, enquanto
                          o índice de envelhecimento evoluiu de 
                          [indice_envelhecimento91]% para [indice_envelhecimento00]%.";

        public function __construct($municipio, $idmunicipio) {
            parent::__construct(&$this->texto,8);
            
            
            $this->setData("subtitulo", "ESTRUTURA ETÁRIA");
            $this->setData("text", $this->pTexto);
        }

        public function getTexto(){
            return parent::getTexto();
        }
        
        public function __destruct() {
            
        }

    }

?>
