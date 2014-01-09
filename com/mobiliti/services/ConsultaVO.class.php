<?php
	class ConsultaVO
	{
		public $espacialidade;
		public $filtros;
		public $indicadores;

		public function __construct($espacialidade,$filtros,$indicadores) {
         	$this->espacialidade =  $espacialidade;  
         	$this->filtros = $filtros;   
         	$this->indicadores = $indicadores;   
        }
	}
?>