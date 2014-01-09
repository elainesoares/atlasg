<?php
    //! important requires conexao.class.php
    
    require_once BASE_ROOT.'config/config_path.php';
    require_once BASE_ROOT."config/man_conexao.class.php";

    class mbd extends MConexao 
    {

        private $ConexaoLink;

        private $Parent = false;
        
        /**
         * @param ConexaoLink $Link (opcional) é a um parametro opcional do link, use somente 
         * quando você já estiver aberto uma conexão, caso não tenha aberto a classe irá
         * automaticamente abrir
         * @param int $Paginacao (opcional) limite de linhas por página 
         */
        public function __construct($Link = null) 
        {
            $this->ConexaoLink = $Link;

            if($this->ConexaoLink == null)
            {
                $this->Parent = true;
                $this->ConexaoLink = parent::open();
            }
        }
        /**
         * Retorna a conexão
         * @return ConexaoLink
         */
        public function getConexaoLink(){
            return $this->ConexaoLink;
        }
        /**
         * 
         * @param strin $Tabela nome da tabela
         * @param array|string $ArrayCampos array com os cambos que você deseja selecionar.
         * Use o formato: $ArrayCampos = Array('nome','estado', (...));
         * @param string $Where (opcional) você pode colocar uma condição, para isso use o where completo
         * @example WHERE x = 1 and b = 'a'
         * 
         */
        protected function Consultar($Tabela,$ArrayCampos,$Where = "",$ColTabela = null)
        {
            if(is_array($ArrayCampos))
                $Fields = implode(",", $ArrayCampos);
            else if(!is_numeric($ArrayCampos))
                $Fields = $ArrayCampos;
            else
                trigger_error("A variável \$ArrayCampos não é um array nem uma string no método Consultar da classe BD!", E_USER_ERROR);

            $SQL = "SELECT $Fields FROM $Tabela $Where ";
            if(!is_null($ColTabela))
                return $this->ExecutarSQLByIndex($SQL, $ColTabela);
            return $this->ExecutarSQL($SQL);
        }

        /**
         * @param string $SQL passe o sql que você desejar executar, lembre-se que
         * só executará comando de leitura (SELECT)
         * @return array Caso a consulta retorne mais de um valor ou linha
         */
        public function ExecutarSQL($SQL,$ident = "|source não definida|")
        {
            try{

                $Resposta = @pg_query($this->ConexaoLink, $SQL) or die ("Nao foi possivel executar a consulta! ".$ident);
                $Return = array();
                //$count = 0;
                while ($Linha = @pg_fetch_assoc($Resposta)){
                    //if($count > 5700) die("FATALITY");
                    $Return[] = $Linha;
                }

            }catch (Exception $e){
                die("ERRO");
            }
            return $Return;
        }
        
        /**
         * @param string $SQL passe o sql que você desejar executar, lembre-se que
         * só executará comando de leitura (SELECT)
         * @param string $ColTabela esta variável recebe o nome de uma das colunas da tabela 
         * (ou conjunto de tabelas) e coloca seu valor como index no retorno. --Use nome de campos chave--
         * @return array Caso a consulta retorne mais de um valor ou linha, o retorno
         * será mais ou menos assim $Arr[Valor ColTabela] = array(...);
         */
        public function ExecutarSQLByIndex($SQL,$ColTabela,$ident = "|source não definida|"){
            
            try{
                $Resposta = @pg_query($this->ConexaoLink, $SQL) or die ("Nao foi possivel executar a consulta! ".$ident);
                $Return = array();
                while ($Linha = @pg_fetch_assoc($Resposta))
                    $Return[$Linha[$ColTabela]] = $Linha;
            }catch (Exception $e){
                die("ERRO");
            }
            return $Return;
        }
        
        public function __destruct()
        {
            if($this->Parent)
            {
                parent::close();
            }
        }
        
        public function insert($SQL,$ident = "|source não definida|"){
		//echo "$SQL \n";
		//parent::statusCon();
            /*
			try {
				pg_query($this->ConexaoLink, $SQL);// or die ("Nao foi possivel executar a consulta! ".$ident);
				//$Resposta = @pg_query($this->ConexaoLink, $SQL);// or die ("Nao foi possivel executar a consulta! ".$ident);
			} catch (Exception $e) {
				//echo 'Exception reçue : ',  $e->getMessage(), "\n";
				echo "Error";
			}*/
			
			$Resposta = @pg_query($this->ConexaoLink, $SQL);// or die ("Nao foi possivel executar a consulta! ".$ident);
			if($Resposta){
                //$insert_row = @pg_fetch_row($Resposta);
				//$insert_id = $insert_row[0];
				//return $insert_id;
				//echo pg_last_oid($Resposta);
            }else{
                //die("ERRO");
            }
        }

    }

?>
