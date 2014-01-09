<?php
$comPath = BASE_ROOT . "/com/mobiliti//";
require_once $comPath . "util/protect_sql_injection.php";
include_once("./config/conexao.class.php");

class Arvore{
    #Declaração das variáveis
    var $divisaoMun1 = 0;
    var $divisaoMun2 = 0;
    var $nomeTratadoMun1 = 0;
    var $nomeTratadoMun2 = 0;
    var $uf1Cru = 0;
    var $uf2Cru = 0;
    var $UrlUf1 = 0;
    var $UrlUf2 = 0;
    var $nomeMun1 = 0;
    var $nomeMun2 = 0;
    var $idMun1 = 0;
    var $idMun2 = 0;
    var $uf1 = 0;
    var $uf2 = 0;
    var $estado1 = 0;
    var $idAno1 = 0;
    var $idAno2 = 0;
    var $Ano1 = 0;
    var $Ano2 = 0;
    var $Ideal1 = 0;
    var $Ideal2 = 0;
    var $espac1 = 0;
    var $espac2 = 0;
    var $divisao1 = 0;
    var $divisao2 = 0;
    
    public function __construct($municipio1Arvore, $municipio2Arvore) {
        if($municipio1Arvore == 'nulo' || $municipio2Arvore != 'nulo' ){
            $this->divisao2 = explode('/', $municipio2Arvore);
            $this->espac2 = $this->divisao2[0];
            $this->divisaoMun2 = explode('_',  $this->divisao2[1]); //Explode Municipio 2
            $stringTratada2 = cidade_anti_sql_injection(str_replace('-', ' ', $this->divisaoMun2[0]));
            $this->nomeTratadoMun2 = $stringTratada2;
            $this->uf2Cru = $this->divisaoMun2[1];
            $stringUf2Tratada = cidade_anti_sql_injection(str_replace('-', ' ', $this->divisaoMun2[1]));
            $this->UrlUf2 = $stringUf2Tratada;
            $this->Ano2 = $this->divisaoMun2[2];
            
            if(isset($this->divisaoMun2[3]))
                $this->Ideal2 = $this->divisaoMun2[3];
            else
                $this->Ideal2 = false;
        }
        
        if($municipio1Arvore != 'nulo' || $municipio2Arvore == 'nulo' ){
            $this->divisao1 = explode('/', $municipio1Arvore);
            $this->espac1 = $this->divisao1[0];
            $this->divisaoMun1 = explode('_',  $this->divisao1[1]); //Explode Municipio 1
            $stringTratada1 = cidade_anti_sql_injection(str_replace('-', ' ', $this->divisaoMun1[0]));
            $this->nomeTratadoMun1 = $stringTratada1;
            $this->uf1Cru = $this->divisaoMun1[1];
            $stringUf1Tratada = cidade_anti_sql_injection(str_replace('-', ' ', $this->divisaoMun1[1]));
            $this->UrlUf1 = $stringUf1Tratada;
            $this->Ano1 = $this->divisaoMun1[2];
            
            if(isset($this->divisaoMun1[3]))
                $this->Ideal1 = $this->divisaoMun1[3];
            else
                $this->Ideal1 = false;
        }
        
        $this->idAnos();
        $this->read();
    }
    
    public function idAnos(){
        //Ano1
        if($this->Ano1 == 2010)
            $this->idAno1 = 3;
        else if($this->Ano1 == 2000)
            $this->idAno1 = 2;
        else if($this->Ano1 == 1991)
            $this->idAno1 = 1;
        //Ano2
        if($this->Ano2 == 2010)
            $this->idAno2 = 3;
        else if($this->Ano2 == 2000)
            $this->idAno2 = 2;
        else if($this->Ano2 == 1991)
            $this->idAno2 = 1;
    }
    
    public function read() {
        if($this->divisao1 != 0){
            if($this->espac1 == 'municipio'){
            $SQLMun1 = "SELECT m.nome as n_mun, e.uf, m.id as id_mun, e.id as id_est, e.nome as n_est
                    FROM municipio m, estado e
                    WHERE m.fk_estado = e.id and sem_acento(m.nome) ILIKE '{$this->nomeTratadoMun1}' AND (e.uf ILIKE '{$this->UrlUf1}' ) LIMIT 1";
            }
            else if($this->espac1 == 'estado'){
                $SQLMun1 = "SELECT e.nome as n_est, e.uf, e.id as id_est
                        FROM estado e
                        WHERE sem_acento(e.nome) ILIKE '{$this->nomeTratadoMun1}' AND (e.uf ILIKE '{$this->UrlUf1}' ) LIMIT 1";
            }
            
            $results1 = $this->executarSQL($SQLMun1);
            if($this->espac1 == 'municipio'){
                $this->nomeMun1 = $results1[0]["n_mun"];
                $this->idMun1 = $results1[0]["id_mun"];
                $this->estado1 = $results1[0]["n_est"];
            }
            else if($this->espac1 == 'estado'){
                $this->nomeMun1 = $results1[0]["n_est"];
                $this->idMun1 = $results1[0]["id_est"];           
            }
            
            $this->uf1 = $results1[0]["uf"];
        }
        
        if($this->divisao2 != 0){
            if($this->espac2 == 'municipio'){
                $SQLMun2 = "SELECT m.nome as n_mun, e.uf, m.id as id_mun, e.id as id_est, e.nome as n_est
                        FROM municipio m, estado e
                        WHERE m.fk_estado = e.id and sem_acento(m.nome) ILIKE '{$this->nomeTratadoMun2}' AND (e.uf ILIKE '{$this->UrlUf2}' ) LIMIT 1";
            }
            else if($this->espac2 == 'estado'){
                $SQLMun2 = "SELECT e.nome as n_est, e.uf, e.id as id_est
                        FROM estado e
                        WHERE sem_acento(e.nome) ILIKE '{$this->nomeTratadoMun2}' AND (e.uf ILIKE '{$this->UrlUf2}' ) LIMIT 1";
            }
            
            $results2 = $this->executarSQL($SQLMun2);
        
            if($this->espac2 == 'municipio'){
                $this->nomeMun2 = $results2[0]["n_mun"];
                $this->idMun2 = $results2[0]["id_mun"];
                $this->estado2 = $results2[0]["n_est"];
            }
            else if($this->espac2 == 'estado'){
                $this->nomeMun2 = $results2[0]["n_est"];
                $this->idMun2 = $results2[0]["id_est"];           
            }
            
             $this->uf2 = $results2[0]["uf"];
        }
        
        
        
        
//        $this->nomeMun2 = $results2[0]["n_mun"];
        
       
        
//        $this->idMun2 = $results2[0]["id_mun"];
        
//        $this->estado2 = $results2[0]["n_est"];
    }
    
    public function executarSQL($SQL){
        $ocon = new Conexao();
        $link = $ocon->open();
        $resultado = pg_query($link, $SQL) or die ("Nao foi possivel executar a consulta! ");
        $IdMun = array();
        while ($Linha = pg_fetch_assoc($resultado)){
            $IdMun[] = $Linha;
        }
        
        return $IdMun;
    }
    
    public function getIds(){
        $Ids = array();
        $Ids[] = $this->idMun1;
        $Ids[] = $this->idMun2;
        return $Ids;
    }
    
    public function getidAnos(){
        $idAnos = array();
        $idAnos[] = $this->idAno1;
        $idAnos[] = $this->idAno2;
        return $idAnos;
    }
    
    public function getAnos(){
        $Anos = array();
        $Anos[] = $this->Ano1;
        $Anos[] = $this->Ano2;
        return $Anos;
    }
    
    public function getNomesMun(){
        $NomesMun = array();
        $NomesMun[] = $this->nomeMun1;
        $NomesMun[] = $this->nomeMun2;
        return $NomesMun;
    }
    
    public function getIdeal(){
        $Ideal = array();
        $Ideal[] = $this->Ideal1;
        $Ideal[] = $this->Ideal2;
        return $Ideal;
    }
    
    public function getEspac(){
        $Espac = array();
        $Espac[] = $this->espac1;
        $Espac[] = $this->espac2;
        return $Espac;
    }
    
    public function getUf(){
        $Uf = array();
        $Uf[] = $this->uf1;
        $Uf[] = $this->uf2;
        return $Uf;
    }
}
?>
