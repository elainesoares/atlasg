<?php
    $comPath = BASE_ROOT."\com\mobiliti\\";
    require_once BASE_ROOT.'config\config_path.php';
    require_once $comPath."consulta\bd.class.php";
    require_once $comPath."util\protect_sql_injection.php";

    /**
     * Description of SubPagePerfil
     *
     * @author Lorran
     */
    class SubPagePerfil extends bd {

        private $nomeCidade;
        private $idCidade;
        private $nomeCru;
        private $UrlNome;
        private $uf;
        private $estado;

        public function __construct($cidade) {
            parent::__construct();

            if($cidade == null || $cidade == ""){

            }
            $this->nomeCru = $cidade;
            $stringTratada = cidade_anti_sql_injection(str_replace('-', ' ', $cidade));
            $this->UrlNome = $stringTratada;
            $this->read();
        }

        public function getIdCidade(){
            return $this->idCidade;
        }

        public function getNomeCidade(){
            return $this->nomeCidade;
        }

        private function read()
        {
            $SQL = "SELECT municipio.nome, uf, municipio.id, estado.nome as nomeestado FROM municipio 
                    INNER JOIN estado ON (municipio.fk_estado = estado.id)
                    WHERE municipio.nome ILIKE '{$this->UrlNome}' LIMIT 1";
            $results = parent::ExecutarSQL($SQL);
            $this->nomeCidade = $results[0]["nome"];
            $this->uf = $results[0]["uf"];
            $this->idCidade = $results[0]["id"];
            $this->estado = $results[0]["nomeestado"];
        }

        public function __destruct() {

        }

    }

?>
