<?php
    /**
        * Created on 26/02/2013
        *
        * Esta classe vai servi para quando você precisar adcionar métodos especificos
        * do seu módulo.
        * 
        * @author Valter Lorran (valter@mobilidade-ti.com.br)
        * @version 1.0.0
        *
        */
    class PublicMethods{
        
        /**
         * Utilize essa propriedade para acessar os métodos e propriedades públicas 
         * da classe Consulta
         * 
         * @var Consulta
         */
        private $Consulta;
        
        public static $ArrayEspacializacao = array(
            'udh',
            'municipal',
            'microrregional',
            'mesorregional',
            'rm',
            'estadual',
            'regional',
            'pais',
            'ri'
        );
        
        public static $ArrayPadding3ZerosDireita = array(196,197,198,199);
        public static $ArrayPadding2ZerosDireita = array(15,17,18,19,20,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,75,76,77,78,79,80,82,83,84,86,88,89,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,133,134,135,136,137,138,139,140,141,142,143,146,147,148,149,150,152,153,154,155,156,157,158,159,160,161,162,163,164,182,183,188,190,191,192,193,194,195,238,200,201,73,74,108,109,132,144,151,165);
        
        public static $ArrayAnos = array(1991,2000,2010);
        
        
        //======================================================================
        //COLOQUE SUAS PROPRIEDADES AQUI
        //======================================================================
        
        //======================================================================
        //PROPRIEDADES ATÉ AQUI
        //======================================================================
        
        /**
         * Created on 26/02/2013
         *
         * Esta classe vai servi para quando você precisar adcionar métodos especificos
         * do seu módulo.
         * 
         * @author Valter Lorran (valter@mobilidade-ti.com.br)
         * @version 1.0.0
         *
         */
        public function __construct($iConsulta) {
            //DO NOT PUT NOTHING HERE
            //NÃO COLOQUE NADA AQUI
            //NO COLOQUE NADA AQUÍ
            $this->Consulta = $iConsulta;
        }
        
        /**
         * NÃO MODIFIQUE ESTE MÉTODO
         * Este método chama a o seu método :)
         * @param string $name
         * @param args $args
         */
        public function CallMethod($name,$args){
            $this->{$name}($args);
        }
        
        //======================================================================
        //MÉTODOS AQUI
        //======================================================================
        


        public static function TranslateTabela($lugares,$indicadores){
            $Retorno = array();
            foreach($lugares as $key=>$val){
                $esp = PublicMethods::converterEspacialidadeParaString($val["e"]);
                $Retorno["espacialidade"][$esp] = array();
                $f = "";

                switch($val["e"]){
                    case 2:
                        $f = 'municipio';
                        break;
                    case 3:
                        $f = "regiao";
                        break;
                    case 4:
                        $f = 'estado';
                        break;
                    case 5:
                        $f = 'udh';
                        break;
                    case 6:
                        $f = 'regiaometropolitana';
                        break;
                    case 7:
                        $f = 'regiaointeresse';
                        break;
                    case 8:
                        $f = 'mesorregiao';
                        break;
                    case 9:
                        $f = 'microrregiao';
                        break;
                    case 10:
                        $f = 'pais';
                        break;
                }
                if($val['ids'] != ""){
                    $arr = explode (',', $val['ids']);
                    foreach($arr as $vFiltro){
                        $Retorno["espacialidade"][$esp][$f][] = $vFiltro;
                    }
                }
                else
                    unset($Retorno["espacialidade"][$esp]);
            }
            $ind = explode(",", $indicadores);
            foreach($ind as $val){
                $ex = explode(";",$val);
                $Retorno["indicadores"][] = array($ex[1],  PublicMethods::TranslateAnoId($ex[0]));
            }
            return $Retorno;
        }
        
        private static function converterEspacialidadeParaString($id){
            switch($id){
                case 2:
                    return 'municipal';
                case 3:
                    return "regional";
                case 4:
                    return 'estadual';
                case 5:
                    return 'udh';
                case 6:
                    return 'regiaometropolitana';
                case 7:
                    return 'regiaointeresse';
                case 8:
                    return 'mesorregiao';
                case 9:
                    return 'microrregiao';
                case 10:
                    return 'pais';
            }
        }
        
        private static function converterFiltroParaString($id){
            switch($id){
                case Filtro::$FILTRO_MUNICIPIO: return 'municipio';
                case Filtro::$FILTRO_ESTADO: return 'estado';
                case Filtro::$FILTRO_REGIAO: return 'regiao';
                case Filtro::$FILTRO_UDH: return 'udh';
                case Filtro::$FILTRO_MICROREGIAO: return 'microrregiao';
                case Filtro::$FILTRO_REGIAOMETROPOLITANA: return 'regiaometropolitana';
                case Filtro::$FILTRO_REGIAODEINTERESSE: return 'regiaodeinteresse';
                case Filtro::$FILTRO_PAIS: return 'pais';
                case Filtro::$FILTRO_MESORREGIAO: return 'mesorregiao';
                case Filtro::$FILTRO_BRASIL: return 'brasil';
            }
        }
        private static function LimparArray(&$Array){
            foreach($Array as $key=>$val){
                if($val == ''){
                    unset($Array[$key]);
                }
            }
        }
        
        public static function TranslateEspacialidade($StringEspacialidade){
            switch ($StringEspacialidade) {
                case 'udh':
                    return 5;
                case 'municipal':
                    return 2;
                case 'microrregional':
                    return 9;
                case 'mesorregional':
                    return 8;
                case 'rm':
                    return 6;
                case 'estadual':
                    return 4;
                case 'regional':
                    return 3;
                case 'pais':
                    return 10;
                case 'regiaointeresse':
                    return 7;
            }
        }
        
        public static function TranslateFiltro($StringFiltro){
            switch ($StringFiltro) {
                case 'municipio':
                    return 1;
                case 'estado':
                    return 2;
                case 'regiao':
                    return 3;
                case 'udh':
                    return 4;
                case 'microregiao':
                    return 5;
                case 'rm':
                    return 6;
                case 'regiaointeresse':
                    return 7;
                case 'pais':
                    return 8;
                case 'mesorregiao':
                    return 9;
                case 'brasil':
                    return 10;
            }
        }
        
        public static function TranslateAno($ano){
            switch ($ano) {
                case 1991:
                    return 1;
                case 2000:
                    return 2;
                case 2010:
                    return 3;
                default :
                    return 0;
            }
        }
        
        public static function TranslateAnoId($id){
            switch ($id) {
                case 1:
                    return 1991;
                case 2:
                    return 2000;
                case 3:
                    return 2010;
                case "1":
                    return 1991;
                case "2":
                    return 2000;
                case "3":
                    return 2010;
                default :
                    return 2010;
            }
        }
        
        public function pmGetFiltroInfo(){
            $ObjetoFiltro = $this->Consulta->getFiltros();
            foreach($ObjetoFiltro as $obj){
                $Valores = $obj->getValores();
                $arrayAreas = array();
                foreach($Valores as $v){
                    $arrayAreas[] = str_replace('-', ' ', $v->getNome());
                }
                $this->gerarSQLFiltro($obj->getFiltro(), $arrayAreas);
            }
        }
        
        private function gerarSQLFiltro($Tipo,$Array){
            $SQLRegiao = array();
            switch ($Tipo){
                case Filtro::$FILTRO_MUNICIPIO:
                    $SQL = "SELECT id FROM municipio WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(nome ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
                case Filtro::$FILTRO_ESTADO:
                    $SQL = "SELECT id,uf FROM estado WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(uf ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
                case Filtro::$FILTRO_REGIAOMETROPOLITANA:
                    $SQL = "SELECT id FROM rm WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(nome ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
                case Filtro::$FILTRO_REGIAODEINTERESSE:
                    $SQL = "SELECT id FROM ri WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(nome ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
                case Filtro::$FILTRO_UDH:
                    $SQL = "SELECT id FROM udh WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(nome ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
                case Filtro::$FILTRO_REGIAO:
                    $SQL = "SELECT id FROM regiao WHERE ";
                    foreach($Array as $val){
                        $SQLRegiao[] = "(nome ILIKE '$val')";
                    }
                    $SQL = $SQL."(".(implode(" OR ", $SQLRegiao)).")";
                    break;
            }
            echo($SQL);
            echo "\n";
        }


        /*
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Retorna um array com id das regiões
         * 
         */
        public static function getRegionID($regions_names,$con)
        {
            
            $link = $con->open();
            $regions = array();
            
            
          // cria a string com os nomes
           for($i = 0; $i < sizeof($regions_names); $i++)
           {
               $regions_to_search = $regions_to_search . " upper('" .  $regions_names[$i] . "'),";
           }
           $regions_to_search = substr($regions_to_search, 0, -1);
            
          
      
            $query = "SELECT id FROM REGIAO WHERE nome in ($regions_to_search);";
            
            $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");
            
            while ($row = pg_fetch_row($res, null, PGSQL_ASSOC)){
                array_push($regions, $row['id']);
            }
            
            return $regions;
        }
        
        
        
         /*
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Retorna um array com id dos etados
         * 
         */
        public static function getStatesID($states_names,$con){
            
            $link = $con->open();
            $states = array();
            
             foreach ($states_names as $value) {
                $states_to_search = $states_to_search . "'" . $value . "',";
            }
            $states_to_search = substr($states_to_search, 0, -1);
            
   
            $query = "SELECT id FROM estado WHERE replace(lower(sem_acento(nome)),' ','_') in ($states_to_search) ";
           
            $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");
            
            while ($row = pg_fetch_row($res, null, PGSQL_ASSOC)){
                array_push($states, $row['id']);
            }
            
            
            
            return $states;
        }
        
        
         /*
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Retorna um array com id das cidades
         * 
         */
        public static function getCityID($cities_names,$con)
        {
           //abre a conexão
           $link = $con->open();
           $cities = array();
           $result = array();
           
           
           //acaba a função
           if($cities_names == null)return;
           

           // cria a string com os nomes
           for($i = 0; $i < sizeof($cities_names); $i++)
           {
               $nome_cidade = $cities_names[$i];    
               
               if(PublicMethods::hasState($nome_cidade))
               {
                   if($nome_cidade != null)$cities_with_state = $cities_with_state .  "replace(lower(sem_acento('$nome_cidade')),' ','_'),";
               }
               else
               {
                   if($nome_cidade != null)$cities_without_state = $cities_without_state .  "replace(lower(sem_acento('$nome_cidade')),' ','_'),";
               }
           }
           
           
           
           //monta a sql de busca com estados
           if($cities_with_state != "")
           {
               //remove a última vírgula
               $cities_with_state = substr($cities_with_state, 0, -1);
               $query_with_state = "SELECT  m.id, replace(lower(sem_acento( (m.nome || '-' || e.uf) )),' ','_') as full_name FROM municipio m INNER JOIN estado e ON m.fk_estado = e.id  WHERE replace(lower(sem_acento( (m.nome || '-' || e.uf) )),' ','_') in ($cities_with_state);";
           
               //monta o array de id de cidades
               $res = pg_query($link, $query_with_state) or die("Nao foi possivel executar a consulta!");

               while ($row = pg_fetch_row($res, null, PGSQL_ASSOC)){
                   $cities[$row['full_name']] = $row['id'] ;
               }
           }
           
           //monta a sql de busca sem estados
           if($cities_without_state != "")
           {
               //remove a última vírgula
               $cities_without_state = substr($cities_without_state, 0, -1);
               $query_without_state = "SELECT DISTINCT ON (m.nome)  m.id, replace(lower(sem_acento( m.nome )),' ','_') as full_name FROM municipio m INNER JOIN estado e ON m.fk_estado = e.id  WHERE replace(lower(sem_acento( (m.nome) )),' ','_') in ($cities_without_state);";
           
               //monta o array de id de cidades
               $res = pg_query($link, $query_without_state) or die("Nao foi possivel executar a consulta!");

               while ($row = pg_fetch_row($res, null, PGSQL_ASSOC)){
                   $cities[$row['full_name']] = $row['id'] ;
               }
               
           }
           
           
          
           foreach ($cities_names as $value) 
           {
              if($cities[$value] != NULL) array_push($result, $cities[$value]);   
           }


           return $result;
            
        }
        
         /*
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Verifica se o nome da cidade está acompanhado do estado, ex: 'Montes Claros-MG'
         * 
         */
        public static function hasState($city)
        {
            $_has = false;
            
            if(strlen ( $city ) > 3)
            {
                $traco_uf = substr($city, -3);
                if($traco_uf[0] == "-")
                {
                    $_has = true;
                }
            }
            
            return $_has;
        }
        
        
        
         /*
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Retorna o id do ano e indicador
         * 
         */
        public static function getIndicator($sigla,$ano,$con){
            
            $link = $con->open();
            $indic = array("indc" => null, "ano" => 3);
            
          
            $query = "SELECT id FROM variavel WHERE sigla = upper('$sigla'); ";
           
            $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");
            $row = pg_fetch_row($res, null, PGSQL_ASSOC);
            if($row) $indic["indc"] = $row["id"];
            
            
            $query = "SELECT id FROM ano_referencia WHERE label_ano_referencia = '$ano'; ";      
            $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");
            $row = pg_fetch_row($res, null, PGSQL_ASSOC);
            if($row) $indic["ano"] = $row["id"];

            
            return $indic;
        }
        
        
        /**
        * converts pixel units into map units
        * @return array  [0]=> X in map units; [1]=> Y in map units
        */
        public static function click2map ($click_x, $click_y, $current_extent, $width, $height) {

               $x_pct = ($click_x / $width);
               $y_pct = 1 - ($click_y / $height);

               $x_map = $current_extent[0] + ( ($current_extent[2] - $current_extent[0]) * $x_pct);
               $y_map = $current_extent[1] + ( ($current_extent[3] - $current_extent[1]) * $y_pct);

               return array($x_map, $y_map);
        }
        
        
        /**
        * converts  map units to pixel units into
        */
        public static function map2click ($coord_x, $coord_y, $current_extent, $width, $height) 
        {
            $min_x = (float)$current_extent[0];
            $min_y = (float)$current_extent[1];
            $max_x = (float)$current_extent[2];
            $max_y = (float)$current_extent[3];
               
            $x_pic = round(((abs($min_x) -  abs($coord_x)) * ( $width / ($max_x - $min_x))),0);
            $y_pic = $height - round(((abs($min_y) -  abs($coord_y)) * ( $height / ($max_y - $min_y))),0);

     
            
            return array("x" => $x_pic, "y" => $y_pic);
        }
        
        
         /*s
         *  @author Reinaldo Aparecido (reinaldo@mobilidade-ti.com.br)
         *  
         *  Retorna o centróide de alguma região;
         * 
         */
        public static function getCentroidPoint($region_id,$con)
        {
            $link = $con->open();
            
            $query = "SELECT nome as label, X(ST_AsText(ST_Centroid(the_geom))) as x, Y(ST_AsText(ST_Centroid(the_geom))) as y FROM municipio WHERE id = $region_id;";
            
            
            $res = pg_query($link, $query) or die("Nao foi possivel executar a consulta!");
            $row = pg_fetch_row($res, null, PGSQL_ASSOC);
     
            return $row;
        }
        
        public static function nameJson($lugar, $indicador){
            $sv = array();
            foreach($lugar as $key=>$val){
                $ids = explode(",",$val['ids']);
                sort($ids);
                $str = implode(',', $ids);
                $sv[] = $val["e"].'|'.$str;
            }
            $inc = explode(',', $indicador);
            sort($inc);
            $resInc = implode(',',$inc);
            $resSv = implode(',', $sv);
            $r = "j".md5($resInc."||".$resSv);
            return $r;
        }

    }

?>
