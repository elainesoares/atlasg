<?php
	require_once "Indicador.class.php";
	require_once "ValorFiltro.class.php";
	require_once "Filtro.class.php";
	require_once('config/conexao.class.php');

	class GetInfoBD
    {
    	/**
		* Recebe a string com o grupo de indicadores e seus respectivos anos.
    	*/
    	public static function getIndicadores($value){
    		$indicadores = array();
    		$grupo_indicadores = explode(",",$value);
    		for($i = 0; $i < count($grupo_indicadores); $i++)
			{
				$indicador_e_ano = explode("-",$grupo_indicadores[$i]);
				
				$indicador;
				$ano;
				if(count($indicador_e_ano) > 1)
				{
					$str_var = "SELECT id,nomecurto FROM variavel WHERE lower(sigla) = '".$indicador_e_ano[0]."'";
					$retorno = self::getRow($str_var);

					$indicador = (int)$retorno['id'];

					$str_ano = "SELECT id FROM ano_referencia WHERE label_ano_referencia = ".$indicador_e_ano[1];
					$ano = (int) self::getId($str_ano);

					$indicador_string = $retorno['nomecurto'];
					
					$ano_string = $indicador_e_ano[1];
				}
				else
				{
					$str_var = "SELECT id,nomecurto FROM variavel WHERE lower(sigla) = '".$indicador_e_ano[0]."';";
					$retorno = self::getRow($str_var);

					$indicador = (int)$retorno['id'];

					$str_ano = "SELECT id FROM ano_referencia WHERE label_ano_referencia = 2010";
					$ano = self::getId($str_ano);

					$indicador_string = $retorno['nomecurto'];
					$ano_string = "2010";
				}
				
				$indicadores.array_push($indicadores, new Indicador($indicador,$ano,$indicador_string,$ano_string));
			}
			return $indicadores;
    	}

    	public static function getFiltros($value)
    	{
    		$filtros = array();
    		$sql;

    		for($j = 0; $j < count($value);)
			{
				$array_de_valores = array();
				
				$tipo = $value[$j];
				
				$somente_valores = explode(",",$value[$j+1]);
				for($h = 0; $h < count($somente_valores); $h++)
				{
					$sql = self::getSQLForType($tipo);
					$valor = $somente_valores[$h];
					
					//substitui os caracteres "_" por espaço em branco
					$valor = str_replace("_"," ",$valor);
					
					//embuti o valor do filtro na consulta
					$sql = str_replace('{parametro}',$valor,$sql);
					$retorno = self::getRow($sql);
					
					$array_de_valores.array_push($array_de_valores,new ValorFiltro($retorno['nome'],(int) $retorno['id']));
				}
				
				$filtro = new Filtro($tipo,$array_de_valores);
				$filtros.array_push($filtros, $filtro);
				
				$j = $j + 2;
			}
			return $filtros;
    	}

    	/**
		* Retorna uma string de acordo com o tipo. 
		* A string possui a marcação {parametro} para ser substituida(usando o str_replace) pelo parametro verdadeiro.
    	*/
    	private function getSQLForType($tipo)
    	{
    		$sql;
    		switch ($tipo) {
			    case "regiao":
			        $sql = "SELECT id,nome FROM regiao WHERE sem_acento(nome) ilike '%'||sem_acento('{parametro}')||'%'";
			        break;
			    case "estado":
			        $sql = "SELECT id,nome FROM estado WHERE sem_acento(nome) ilike '%'||sem_acento('{parametro}')||'%'";
			        break;
			    case "microregiao":
			        $sql = "SELECT id,nome FROM microregiao WHERE sem_acento(nome) ilike '%'||sem_acento('{parametro}')||'%'";
			        break;
			    case "municipio":
			    	$sql = "SELECT id,nome FROM municipio WHERE sem_acento(nome) ilike '%'||sem_acento('{parametro}')||'%'";
			        break;
			}
			return $sql;
    	}

    	private function getId($SQL){
    		$minhaConexao = new Conexao();
        	$con = $minhaConexao->open();

            $Resposta = pg_query($con, $SQL) or die ("Nao foi possivel executar a consulta!");
            $Return = pg_fetch_array($Resposta);
            
            return (int)$Return["id"];
        }

        private function getRow($SQL){
    		$minhaConexao = new Conexao();
        	$con = $minhaConexao->open();

            $Resposta = pg_query($con, $SQL) or die ("Nao foi possivel executar a consulta!");
            $Return = pg_fetch_array($Resposta);
            
            return $Return;
        }
    }
?>