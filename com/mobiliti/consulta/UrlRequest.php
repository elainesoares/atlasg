<?php
	class UrlRequest
    {
    	public $espacialidade;
    	public $array_filtros;
    	public $array_indicadores;

    	public function __construct($espacialidade, $array_filtros,$array_indicadores) {
    		$this->espacialidade = $espacialidade;
    		$this->array_filtros = $array_filtros;
    		$this->array_indicadores = $array_indicadores;
    	}
    }
?>