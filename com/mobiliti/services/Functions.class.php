<?php
	require_once('config/conexao.class.php');
	require_once('com/mobiliti/consulta/Filtro.class.php');
	require_once('com/mobiliti/consulta/Consulta.class.php');

	class Functions
	{
		public static function getRow($SQL)
		{
    		$minhaConexao = new Conexao();
        	$con = $minhaConexao->open();

            $Resposta = pg_query($con, $SQL) or die ("Nao foi possivel executar a consulta!");
            $Return = pg_fetch_array($Resposta);
            
            return $Return;
        }

        public static function getEspacialideByName($value)
        {
        	$espacialidade;
        	switch ($value)
        	{
			    case "municipal":
			        $espacialidade = Consulta::$ESP_MUNICIPAL;
			        break;
			    case "regional":
			        $espacialidade = Consulta::$ESP_REGIONAL;
			        break;
			    case "estadual":
			        $espacialidade = Consulta::$ESP_ESTADUAL;
			        break;
			    case "udh":
			        $espacialidade = new Consulta::$ESP_UDH;
			        break;
			    case "rm":
			        $espacialidade = new Consulta::$ESP_REGIAOMETROPOLITANA;
			        break;
			    case "ri":
			        $espacialidade = new Consulta::$ESP_REGIAODEINTERESSE;
			        break;
			    case "mesorregional":
			        $espacialidade = new Consulta::$ESP_MESOREGIAO;
			        break;
			    case "pais":
			        $espacialidade = new Consulta::$ESP_PAIS;
			        break;
			    case "mesorregional":
			        $espacialidade = new Consulta::$ESP_MICROREGIAO;
			        break;
			    default:
			    	$espacialidade = 0;
			}
			return $espacialidade;
        }

        public static function getFiltroByName($value)
        {
        	$filtro;
        	switch ($value)
        	{
			    case "municipio":
			        $filtro = Filtro::$FILTRO_MUNICIPIO;
			        break;
			    case "estado":
			        $filtro = Filtro::$FILTRO_ESTADO;
			        break;
			    case "regiao":
			        $filtro = Filtro::$FILTRO_REGIAO;
			        break;
			    case "udh":
			        $filtro = Filtro::$FILTRO_UDH;
			        break;
			    case "microregiao":
			        $filtro = Filtro::$FILTRO_MICROREGIAO;
			        break;
			    case "rm":
			        $filtro = Filtro::$FILTRO_REGIAOMETROPOLITANA;
			        break;
			    case "ri":
			        $filtro = Filtro::$FILTRO_REGIAODEINTERESSE;
			        break;
			    default:
			    	$filtro = 0;
			}

			return $filtro;
        }
	}

?>