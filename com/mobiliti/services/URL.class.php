<?php

	class URL
	{
		public static function urlToJson($url)
		{	
			$url = self::multiexplode(array("tabela","mapa","histograma","grafico","arvore"),$url);
			$url_tabela = $url[1];
			$url_mapa = $url[2];
			$url_histograma = $url[3];
			$url_grafico = $url[4];
			$url_arvore = $url[5];
			$arr = array ('tabela'=>$url_tabela,'mapa'=>$url_mapa,'histograma'=>$url_histograma,'grafico'=>$url_grafico,'arvore'=>$url_arvore);	
			return json_encode($arr);
		}

		function multiexplode ($delimiters,$string) 
		{
	       	$ready = str_replace($delimiters, $delimiters[0], $string);
		    $launch = explode($delimiters[0], $ready);
		    return  $launch;
		}
	}
?>