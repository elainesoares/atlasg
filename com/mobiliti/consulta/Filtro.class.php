<?php
    require_once 'ValorFiltro.class.php';
    /**
        * Created on 22/02/2013
        *
        * Classe para controlar os filtros
        * 
        * @author Valter Lorran (valter@mobilidade-ti.com.br)
        * @version 1.0.1
        *
        */
    class Filtro {
        
        private $tipo;
        
        private $valores = array();
        
        public static $FILTRO_MUNICIPIO = 1;
        public static $FILTRO_ESTADO = 2;
        public static $FILTRO_REGIAO = 3;
        public static $FILTRO_UDH = 4;
        public static $FILTRO_MICROREGIAO = 5;
        public static $FILTRO_REGIAOMETROPOLITANA = 6;
        public static $FILTRO_REGIAODEINTERESSE = 7;
        public static $FILTRO_PAIS = 8;
        public static $FILTRO_MESORREGIAO = 9;
        public static $FILTRO_BRASIL = 10;
        
        public function __construct($Tipo,$Valores) {
            $this->setFiltro($Tipo, $Valores);
        }
        
        private function setFiltro($Tipo,$Valores){
            $this->tipo = $Tipo;
            foreach($Valores as $val){
                $this->valores[] = new ValorFiltro($Tipo, $val);
            }
        }
        
        public function getFiltro(){
            return $this->tipo;
        }
        
        public function getValores(){
            return $this->valores;
        }
        
        public function getSQL($Espacialidade){
            
        }
    }

?>
