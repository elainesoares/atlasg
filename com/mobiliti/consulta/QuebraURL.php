<?php
	require_once "Filtro.class.php";
	require_once "ConvertUrlToObject.php";
	require_once "Consulta.class.php";
	require_once "UrlRequest.php";

	class QuebraURL
    {
    	public static function leURL($es)
    	{
    		$espacialidade = explode("/espacialidade/",$es);
			$arrayUrl = array();

			for ($i = 1; $i < count($espacialidade); $i++) 
			{
				$arrayFilter = array();
				$arrayIndicadores = array();

			    $filtro = explode("/filtro/",$espacialidade[$i]);
				
				$tipoEspacialidade = $filtro[0];
				
				$str = $filtro[1];
				$tipos_filtros_e_indicadores = explode("/indicadores/",$str);
				
				$filtros = $tipos_filtros_e_indicadores[0];
				$indicadores = $tipos_filtros_e_indicadores[1];
				
				$valores_e_tipos = explode("/",$filtros);
				
				$arrayFilter = ConvertUrlToObject::getFiltros($valores_e_tipos);

				$arrayIndicadores = ConvertUrlToObject::getIndicadores($indicadores);
				var_dump($arrayFilter);
				var_dump($arrayIndicadores);
				$arrayUrl.array_push($arrayUrl,new UrlRequest($tipoEspacialidade,$arrayFilter,$arrayIndicadores));
			}

			return $arrayUrl;
    	}
    }
?>