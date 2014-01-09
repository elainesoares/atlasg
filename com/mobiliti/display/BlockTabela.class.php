<?php
    
    class BlockTabela {
        
        private $boxes = array();
        private $rows;
        private $coluns;
        private $title;
        private $template;
        
        public function __construct($title,$rows,$coluns) {
            $this->rows = $rows;
            $this->coluns = $coluns;
            $this->title = $title;
            $this->template = new Template(5);
        }
        
        public function addBox($title,$val){
            $this->boxes[] = new Box($title, $val);
        }
        
        public function setManual($key,$val){
            $this->template->set($key, $val);
        }
        
        private function buildStruct(){
            $this->template->setTitle($this->title);
            $resultBuilder = "";
            for($i = 0, $k = 0; $i < $this->rows; $i++){
                $resultBuilder .= "<div class='clear'></div>";
                for($y = 0; $y < $this->coluns; $y++, $k++){
                    $resultBuilder .= $this->boxes[$k]->getContent();
                }
            }
            $this->template->set('blocks',$resultBuilder);
        }
        
        public function draw(){
            $this->buildStruct();
            echo $this->template->getHTML();
        }
    }
    
    class Box{
        
        private $title;
        private $val;
        private $template;
        
        public function __construct($_title,$_val) {
            $this->title = $_title;
            $this->val = $_val;
            $this->template = new Template(14);
            $this->template->setTitle($_title);
            $this->template->set('valor',$_val);
        }
        
        public function getContent(){
            return $this->template->getHTML();
        }
    }
?>
