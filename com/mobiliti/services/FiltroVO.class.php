<?php
	class FiltroVO
	{
		public $tipo;
		public $valores;

		public function __construct($tipo,$valores) {
         	$this->tipo =  $tipo;  
         	$this->valores = $valores;   
        }
	}
?>