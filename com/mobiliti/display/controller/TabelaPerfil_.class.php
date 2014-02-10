<?php

    /**
     * Description of TabelaPerfil
     *
     * @author Lorran
     */
    class TabelaPerfil {
        
        private $columns = array();
        private $titleColumns = array();
        
        public function __construct() {
            
        }
        /**
         *
         */
        public function addColun($array){
            $array = array(
                "Faixa etária (anos)",
                "Taxa de analfabetismo"=>array(
                    "91","00","01"
                ),
                "% com 4 ou menos anos de estudo"=>array(
                    "91","00","01"
                )
            );

            foreach($array as $key=>$val){
                if(is_array($val)){
                   $this->addArrayColumn($key,$val);
                }else
                   $this->addSigleColumn($val);
            }
        }

        private function addArrayColumn($key,$val){
            $this->titleColumns[] = new TitleColumn($key, count($val));
            foreach($val as $key){
                $this->columns[] = new Column($key);
            }
        }

        private function addSigleColumn($val){

        }

        public function addRow(){

        }
    }
    
    class Column{
        private $text;
        
        public function __construct($text) {
            $this->text = $text;
        }
        
        public function getHTML(){
            return "<td>{$this->text}</td>";
        }
    }
    
    class TitleColumn{
        
        private $text;
        private $colspan;
        
        public function __construct($text = '',$colspan = 1) {
            $this->text = $text;
            $this->colspan = $colspan;
        }
        
        public function getHTML(){
            return "<td colspan='{$this->colspan}'>{$this->text}</td>";
        }
    }

?>
