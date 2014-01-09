<?php
	class IndicadorVO
	{
		public $idAno;
		public $idVariavel;
		public $stringAno;
		public $stringVariavel;

		public function __construct($idAno,$idVariavel,$stringAno,$stringVariavel) {
         	$this->idAno =  $idAno;  
         	$this->idVariavel = $idVariavel;   
         	$this->stringAno = $stringAno;   
         	$this->stringVariavel = $stringVariavel;   
        }
	}
?>