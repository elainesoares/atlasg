<?php
    class HistogramResponse{
        private $histograma;
        
        function __construct($Histograma){
            $this->histograma = $Histograma;
        }
        
        public function gethistograma(){
	return $this->histograma;
        }
        
        public function getJSON(){
            return json_encode($this->gethistograma()); 
        }
    }
?>