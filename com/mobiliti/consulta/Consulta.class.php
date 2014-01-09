<?php

    //! important requires conexao.class.php

    
    require_once MOBILITI_PACKAGE."consulta/bd.class.php";
    require_once "Indicador.class.php";
    require_once "Filtro.class.php";
    require_once MOBILITI_PACKAGE."util/PublicMethods.class.php";
    /**
        * Created on 21/02/2013
        *
        * Classe que vai controlar a url
        * **Lembre-se de destruir o objeto assim que parar de usa-lo**
        * 
        * @author Valter Lorran (valter@mobilidade-ti.com.br)
        * @version 1.0.1
        *
        */

    class Consulta extends bd{
        
        //======================================================================
        //Propriedades
        //======================================================================
        
        /**
            * 
            * @var string
            */
        public static $ESP_MUNICIPAL = 2;
        public static $ESP_REGIONAL = 3;
        public static $ESP_ESTADUAL = 4;
        public static $ESP_UDH = 5;
        public static $ESP_REGIAOMETROPOLITANA = 6;
        public static $ESP_REGIAODEINTERESSE = 7;
        public static $ESP_MESOREGIAO = 8;
        public static $ESP_MICROREGIAO = 9;
        public static $ESP_PAIS = 10;

        
        
        private $espacialidade;
        
        public $filtros = array();
        public $indicadores = array();
        
        private $objPublicMethods;
        
        //======================================================================
        //Construtor
        //======================================================================
        
        /**
         * @param string|json $Json passe o json com todas as informações da url
         */
        public function __construct($Espacialidade, $Filtros = null) {
            $this->espacialidade = $Espacialidade;
            $this->filtros = $Filtros;
            $this->indicadores;
            parent::__construct();
            $this->objPublicMethods = new PublicMethods($this);
        }
        
        /**
         * Método que serve para implementação de novos métodos
         * @param string $name Nome do método que DEVE estar na classe publicMethods.class.php
         * @param args $arguments Argumentos opcionais.
         */
        public function __call($name, $arguments) {
            $this->objPublicMethods->CallMethod($name, $arguments);
        }
        //======================================================================
        //Métodos Privados
        //======================================================================
        
        /**
         * Método para pegar todas as informações de um indicador, nome, sigla e
         * etc. 
         * @depends getInfoIndicadores
         * @param int|array $id Recebe um único id ou um conjuto de ids
         * @return array Retorna um array se você passar só um id ou um array 
         * multidimensional caso passe mais de um array
         */
        private function iGetInfoIndicador($id){
            if(is_array($id)){
                $formatar = implode(',', $id);
                return parent::Consultar("variavel", array('nomecurto,id,sigla'), "WHERE id IN ($formatar)",'id');
            }else{
                return parent::Consultar("variavel", array('nomecurto,id'), "WHERE id ILIKE '$id'",'id');
            }
        }
        //======================================================================
        //Getters and Setters
        //======================================================================
        /**
         * Adiciona um Filtro e seu respectivo valores
         * @param int $TipoFiltro Tipo do filtro.
         * @param int/array $ValoresFiltro Valores no filtro.
         */
        public function addFiltro($TipoFiltro,$ValoresFiltro){

            $this->filtros[] = new Filtro($TipoFiltro,$ValoresFiltro);
        }
        /**
         * Adiciona um Indicador 
         * @param int $CodIndicador id do Indicador
         * @param int $AnoIndicador id do Ano do Indicador
         */
        public function addIndicador($CodIndicador,$AnoIndicador,$NomeIndicador,$StringAno){
            $this->indicadores[] = new Indicador($CodIndicador,$AnoIndicador,$NomeIndicador,$StringAno);
        }

        /**
         * Retorna um array de objetos do tipo Filtro
         * @
         * @return array/Filtro
         */

        public function getFiltros(){
            return $this->filtros;
        }
        
        /**
         * Retorna um array de objetos do tipo Indicador
         * @return array/Indicadores
         */
        public function getIndicadores(){
            return $this->indicadores;
        }
        /**
         * Retorna informações da variável (nomecurto|sigla)
         * @param int/array $id id(s) do indicadores que você precisa
         * @return array/multidimensional
         */
        public function getInfoIndicador($id){
            return $this->iGetInfoIndicador($id);
        }
        
        /**
         * Retorna o código da espacialidade
         * @return int
         */
        public function getEspacialidade(){
            return $this->espacialidade;
        }
        
        /**
         * Destrutor da classe Consulta
         */
        public function __destruct() {
            parent::__destruct();
        }
        /**
         * Executa um sql pela classe bd.class.php
         * 
         * @param string $SQL SQL de consulta
         * @return Array
         */
        public function bdExecutarSQL($SQL,$ident = "|source não definida|"){
            return parent::ExecutarSQL($SQL,$ident);
        }
        
        public static function parse($Json){
            $Json = json_encode($Json);
            $Consultas = array();    
            $Decoded = json_decode($Json);
            foreach($Decoded as $key=>$val){
                $TConsultas = new Consulta($val->espacialidade);
                foreach($val->array_filtros as $keyFiltro=>$valFiltro){
                    $TConsultas->addFiltro($valFiltro->tipo,$valFiltro->array_valores);
                }
                foreach($val->array_indicadores as $keyIndc=>$valIndc){
                    $TConsultas->addIndicador($valIndc->idVariavel, $valIndc->idAno,$valIndc->nome_string,$valIndc->ano_string);
                }
                $Consultas[] = $TConsultas;
            }
            return $Consultas;
        }

        public static function tableParse($Array){
            $Consultas = array();
            $NewIndicadores = array();
            foreach($Array['espacialidade'] as $key=>$val){
                $TConsultas = new Consulta(PublicMethods::TranslateEspacialidade($key));
                foreach($val as $k=>$v){
                    $TConsultas->addFiltro(PublicMethods::TranslateFiltro($k),$v);
                }
                if(empty($NewIndicadores)){
                    foreach($Array['indicadores'] as $key){
                        if($key[0] == ""){
                            $key[0] = "idhm";
                            $key[1] = 2010;
                            Tabela::$JSONSaved["correcao"] = array("correcao"=>"addIndicador");
                        }
                        $SQL = "select nomecurto,id FROM variavel WHERE id = {$key[0]}";
                        $info = $TConsultas->bdExecutarSQL($SQL);
                        $NewIndicadores[$info[0]['id'].'.'.$key[1]] = array($info[0]['nomecurto'],$key[0],$key[1]);
                        
                        /*
                         * 
                        if(!isset(Consulta::$consultaParse[$key[0]])){
                            $SQL = "select nomecurto,id FROM variavel WHERE sigla ILIKE '{$key[0]}'";
                            $info = $TConsultas->bdExecutarSQL($SQL);
                            Consulta::$consultaParse[$key[0]] = $info;
                        }
                        $NewIndicadores[Consulta::$consultaParse[$key[0]][0]['id'].'.'.$key[1]] = array(Consulta::$consultaParse[$key[0]][0]['nomecurto'],$key[0],$key[1]);
                         * 
                         */
                    }
                    ksort($NewIndicadores);
                }
                foreach($NewIndicadores as $indKey=>$indVal){
                    $arr = explode('.',$indKey);
                    $idAno = PublicMethods::TranslateAno($indVal[2]);
                    $TConsultas->addIndicador($arr[0], $idAno,$indVal[0],$indVal[2]);
                }
                $Consultas[] = $TConsultas;
            }
            return $Consultas;
        }

    }

?>
