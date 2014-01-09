<?php


    /**
        * Created on 22/02/2013
        *
        * Classe para controlar os indicadores
        * 
        * @author Valter Lorran (valter@mobilidade-ti.com.br)
        * @version 1.0.1
        * 
        */
    class Indicador {
        
        /**
         * Esta variável armazenará o indicador
         *
         * @var string 
         */
        private $codIndicador;
        /**
         * 
         * @var int
         */
        private $anoIndicador;
        /**
         * 
         * @var int
         */
        private $anoString;
        /**
         * 
         * @var string
         */
        private $nomeInidicador;
        
        /**
         * Created on 22/02/2013
         *
         * Classe para controlar os indicadores
         *  
         * @author Valter Lorran (valter@mobilidade-ti.com.br)
         * @version 1.0.1
         *
         * @param int $Indicador Código do indicador
         * @param int $Ano Ano do indicador
         */
        public function __construct($CodIndicador,$AnoIndicador,$NomeIndicador,$StringAno) {
            if($AnoIndicador == null){
                $AnoIndicador = 3;
            }
            $this->setIndicador($CodIndicador, $AnoIndicador,$NomeIndicador,$StringAno);
        }
        /**
         * Seta o indicador e seu respectivo ano
         * @param int $Indicador Código do indicador
         * @param int $Ano Ano do indicador
         */
        public function setIndicador($Indicador,$Ano,$NomeIndicador,$StringAno){
            $this->codIndicador = $Indicador;
            $this->anoIndicador = $Ano;
            $this->nomeInidicador = $NomeIndicador;
            $this->anoString = $StringAno;
        }
        /**
         * Retorna o código do indicador
         * @return int
         */
        public function getIndicador(){
            return $this->codIndicador;
        }
        /**
         * Retorna o código do ano
         * @return int
         */
        public function getIndicadorAno(){
            return $this->anoIndicador;
        }
        /**
         * Retorna o nome do indicador
         * @return string
         */
        public function getIndicadorNome(){
            return $this->nomeInidicador;
        }
        /**
         * Retorna o ano em formato de string
         * @return int/string
         */
        public function getIndicadorAnoString(){
            return $this->anoString;
        }
    }
?>
