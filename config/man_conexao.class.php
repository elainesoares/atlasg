<?php


Class MConexao {
    /* =============== VARIÁVEIS DE CONEXÃO AO BANCO DE DADOS ================= */


    /*private $host = "localhost";
    private $user = "postgres";
    private $port = "5432";
    private $pswd = "123";
    private $dbname = "metricas";*/
      
    private $host = "66.71.249.90";
    private $user = "ipea2012";
    private $port = "5433";
    private $pswd = "Ipea2012@Mob";
    private $dbname = "atlas_metricas";

    protected $con = null;

    #Método construtor
    function __construct() {
        
    }

    #Inicia conexao
    public function open() {
        $this->con = @pg_connect("host=$this->host port=$this->port user=$this->user password=$this->pswd dbname=$this->dbname");
        return $this->con;
    }

    #Encerra a conexao
    public function close() {
        @pg_close($this->con);
    }

    #Retorna o nome do Host
    public function getHost() {
        return $this->host;
    }

    #Retorna o nome de Usuário de Acesso ao Banco de Dados
    public function getUser() {
        return $this->user;
    }

    #Retorna a senha do Banco de Dados
    public function getPassword() {
        return $this->pswd;
    }

    #Retorna o nome do Banco de Dados
    public function getNameBd() {
        return $this->dbname;
    }

    #Recupera a porta de conexão ao Banco de Dados
    public function getPort() {
        return $this->port;
    }

    #m�todo verifica status da conexao
    /*function statusCon(){
	    if(!$this->con){
			echo "<h3>O sistema não está conectado!  [$this->dbname] em [$this->host].</h3>";
            exit;
        }else{
            echo "<h3>O sistema está conectado!  [$this->dbname] em [$this->host].</h3>";
        }
    }*/
}

?>