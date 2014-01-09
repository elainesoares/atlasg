<?php
	require_once "ValorVO.class.php";
	require_once "FiltroVO.class.php";
	require_once "ConsultaVO.class.php";
	require_once "Functions.class.php";

	class Mapa_URL
	{
		public static function leURL($url)
    	{
    		$espacialidade;
    		$array_filtros = array();
    		$array_indicadores = array();
    		$url = explode("/",$url);
    		  
    		for($i = 0; $i < count($url); $i++)
			{
				if($i == 1)
				{
					$espacialidade = $url[$i];
				}
				if($url[$i] == 'filtro')
				{
					$nomeFiltro = $url[$i+1];
					$array_valores = array();

					$valores = explode(",",$url[$i+2]);
					
					for($j = 0; $j < count($valores); $j++)
					{
						$array_valores.array_push($array_valores, $valores[$j]);
					}

					$filtro = new FiltroVO($nomeFiltro,$array_valores);
					$array_filtros.array_push($array_filtros, $filtro);
				}
				if($url[$i] == 'indicador')
				{
					$indicadorComAno = $url[$i+1];
					$indicadorComAno = explode("-",$indicadorComAno);
					
					$nomeIndicador = $indicadorComAno[0];
					$ano;

					if($indicadorComAno[1] == NULL){
						$ano = 2010;
					}
					else{
						$ano = $indicadorComAno[2];
					}
					
					$str_var = "SELECT id,nomecurto FROM variavel WHERE lower(sigla) = '".$nomeIndicador."'";
					$retorno = Functions::getRow($str_var);
					var_dump($retorno);
					//$array_indicadores.array_push($array_indicadores, new IndicadorVO());
				}
			}
			$consultaVO = new ConsultaVO($espacialidade,$filtros,$array_indicadores);
    		//var_dump($array_filtros);
    		return $array_filtros;
    	}
	}
?>