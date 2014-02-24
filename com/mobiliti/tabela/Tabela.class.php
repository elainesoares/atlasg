<?php
    /**
      * Created on 18/02/2013
      *
      * Classe para manipular a tabela .
      * Colocarei 't' no inicio de cada variável que vai ser recebida por parametro.
      * Maiscula em cada inicio de palavra
      * 
      * @author Valter Lorran (valter@mobilidade-ti.com.br)
      * @version 1.0.0
      *
      */

    function cutNumber($num,$casas_decimais,$d = null,$f = null){
//        $num = str_replace(".", ",", $num);
        if(!strpos($num, '.')){
            return $num;
        }
        $ex = explode('.', $num);
        if(strlen($ex[1]) >= $casas_decimais){
            return substr($num,0,strpos($num,'.')+$casas_decimais+1);
        }else{
            $con = "";
            for($diff = $casas_decimais-  strlen($ex[1]); $diff > 0; $diff--){
                $con .= "0";
            }
            return $num.$con;
        }
    }
    class Tabela extends bd
    {
        public static $JSONSaved;
        public static $JSONSavedIndicadores;
        public static $lang;
        /**
            * Armazema o controlador da consulta
            * @var Consulta
            */
        private $consulta;
        
        /**
            * Armazema a quantidade de municpios a ser exibido
            * @var int
            */
        private $LimiteExibicao;
        
        /**
            * Página atual da consulta
            * @var int
            */
        private $PaginaAtual;
        
        private $Count;
        
        private $results;
        
        private $Esp;
        
        private $varOnly;
        
        private $isSearchName;
        /**
         * 
         * Classe para manipular a tabela.
         * @param UrlController $iConsulta Aqui você passa o objeto UrlController
         * @param int $iLimiteExibicao Quantidade de cidades que podem ser exibidas 
         * ao mesmo tempo
         * @param int $iPaginaAtual página atual para pesquisa
         * @param string $iOrderBy Ordenação da tabela
         */
        public function __construct($iConsulta, $iLimiteExibicao,$_varOnly,$isSearchName)
        {
            try
            {
                $iPaginaAtuall = 1;
                $iEsp = 1;
                //die(var_dump($iConsulta));
                parent::__construct();
                $this->varOnly = $_varOnly;
                $this->consulta = $iConsulta;
                $this->LimiteExibicao = $iLimiteExibicao;
                $this->PaginaAtual = $iPaginaAtuall;
                $this->isSearchName = $isSearchName;
                $this->Esp = $iEsp;
                $IntLimit = $this->LimiteExibicao;

//                $iOrderBy == 0 ? $iOrderBy = null:1;
//                $Ano == 0 ? $Ano = null:1;
//
//                if($iOrderBy != null || $Ano != null){
//                    if($tipo == 1)
//                        $Limit = " ORDER BY valor DESC LIMIT $IntLimit OFFSET (($IntLimit*$iPaginaAtuall) - ($IntLimit))";
//                    else if($tipo == -1)
//                        $Limit = " ORDER BY valor ASC LIMIT $IntLimit OFFSET (($IntLimit*$iPaginaAtuall) - ($IntLimit))";
//                }else{
//                    $Limit = $this->getLimite($this->consulta,$IntLimit,$iPaginaAtuall);
//                    if($tipo == 1)
//                        $Limit = str_replace ('{ORDER}', 'ASC', $Limit);
//                    else if($tipo == -1)
//                        $Limit = str_replace ('{ORDER}', 'DESC', $Limit);
//                }
                
                $this->Count = 0;
                $arrayAreas = "";
                $sqlSecundaria = $this->getSQLSecundario($arrayAreas);
                $this->getSQLResultsSecundario($this->results,$sqlSecundaria);
            }  catch (Exception $e){
                die("erro :-)");
            }
        }
        
        private function getLimite($Espacialidade,$IntLimit,$iPaginaAtuall){
            
            switch ($this->consulta->getEspacialidade()){
                case Consulta::$ESP_MUNICIPAL:
                    return " ORDER BY m.nome {ORDER} LIMIT $IntLimit OFFSET (($IntLimit*$iPaginaAtuall) - ($IntLimit))";
                case Consulta::$ESP_ESTADUAL:
                    return " ORDER BY e.nome {ORDER} LIMIT $IntLimit OFFSET (($IntLimit*$iPaginaAtuall) - ($IntLimit))";
                case Consulta::$ESP_REGIAODEINTERESSE:
                    return " ORDER BY ri.nome {ORDER} LIMIT $IntLimit OFFSET (($IntLimit*$iPaginaAtuall) - ($IntLimit))";
                case Consulta::$ESP_PAIS:
                    return " ORDER BY p.nome {ORDER} LIMIT 1";
                /*=========================================================
                 * Novas Espacialidades aqui!
                 *=========================================================*/
            }
        }
        
        public function DrawTabela()
        {
            Tabela::$JSONSavedIndicadores = $this->getNomeVariaveis();
            if(!$this->varOnly)
                Tabela::$JSONSaved[] = $this->results;
            else
                Tabela::$JSONSaved[] = $this->results;
        }
        
        private function setArrayValoresVariaveis($Array) {
            $this->ArrayValoresVariaveis = $Array;
        }
        
        private function getSQLMain($IdVariavel,$AnoVariavel){
            $ParteInicialSQL = "";
            $SQLVariaveis = array();
            
            $ParteInicialSQL = $this->getSelect();
            $SQL1 = $this->getSelectFiltroMain();
            if($SQL1 == "")
                $SQL1 = "1=1";
            if(!is_null($IdVariavel) && !is_null($AnoVariavel))
                $SQL2 = "(fk_ano_referencia = $AnoVariavel AND fk_variavel = $IdVariavel)";
            else
                $SQL2 = "(fk_ano_referencia = 1 AND fk_variavel = 185)";
            
            return "$ParteInicialSQL ($SQL1) and ($SQL2)";
        }
        
        private function iGetSQLSecundario($ArrayId){
            $SQLVariaveis = array();
            
            //$INClause = implode(',',$ArrayId);
            $ParteInicialSQL = $this->getSelect();
            
            $Indicadores = $this->consulta->getIndicadores();
            for($x = 0; $x < count($Indicadores);$x++){
                $SQLVariaveis[] = "(fk_ano_referencia = {$Indicadores[$x]->getIndicadorAno()} AND fk_variavel = {$Indicadores[$x]->getIndicador()})";
            }
            $SQL2 = implode(' OR ',$SQLVariaveis);
            $ORDER = "";
            $filtros = $this->getSelectFiltroMain();
            switch ($this->consulta->getEspacialidade()) {
                case Consulta::$ESP_MUNICIPAL:
                    //$where = " fk_municipio IN ($INClause)";
                    $ORDER = " ORDER BY m.nome,fk_variavel,fk_ano_referencia";
                    return "$ParteInicialSQL ($filtros) and ($SQL2) order by e.nome, m.nome";
                    break;
                case Consulta::$ESP_ESTADUAL:
                    //$where = " fk_estado IN ($INClause)";
                    $ORDER = " ORDER BY e.nome,fk_variavel,fk_ano_referencia";
                    break;
                case Consulta::$ESP_REGIAODEINTERESSE:
                    //$where = " fk_estado IN ($INClause)";
                    $ORDER = " ORDER BY e.nome,fk_variavel,fk_ano_referencia";
                    return "$ParteInicialSQL ($filtros) and ($SQL2) order by ri.nome,e.nome, m.nome";
                    break;
                case Consulta::$ESP_PAIS:
                    //$where = " fk_estado IN ($INClause)";
                    return "$ParteInicialSQL ($filtros) and ($SQL2) ";
                    break;
                
                
                /*=========================================================
                 * Novas Espacialidades aqui!
                 *=========================================================*/
            }
            //die("$ParteInicialSQL ($SQL2) and $filtros $ORDER");
            return "$ParteInicialSQL ($filtros) and ($SQL2)";
        }
        
        private function getNomeVariaveis(){
            $ltemp = Tabela::$lang;
            $Indicadores = $this->consulta->getIndicadores();
            for($x = 0; $x < count($Indicadores);$x++){
                $tmp_indcs[] = $Indicadores[$x]->getIndicador();
            }
            $str = implode(',',$tmp_indcs);
            $SQL = "SELECT lang_var.nomecurto,variavel.sigla,variavel.id,lang_var.definicao FROM variavel INNER JOIN lang_var on (variavel.id = lang_var.fk_variavel) WHERE variavel.id IN ($str) and lang_var.lang ILIKE '$ltemp' ORDER BY variavel.id";
            return parent::ExecutarSQLByIndex($SQL,'id',"getNomeVariavels");
        }
        /**
         * Pega o retorno do SQL
         * @param string $SQL recebe a sql e executa
         * @example <br />array(1) {
         *   <br />[2365]=>
         *   <br />array(2) {
         *     <br />["nome"]=>
         *     <br />string(19) "ABADIA DOS DOURADOS"
         *     <br />[0]=>
         *     <br />array(3) {
         *       <br />["valor"]=>
         *       <br />string(6) "72.936"
         *       <br />["fk_ano_referencia"]=>
         *       <br />string(1) "3"
         *       <br />["fk_variavel"]=>
         *       <br />string(1) "1"
         *     <br />}
         *   <br />}
         */
        
        private function iRunSQLSecundario($ResultadosMain,$SQL){
            if(strpos($SQL, "()")){
                die(json_encode(array("erro"=>1,"msg"=>" Houve um erro desconhecido no servidor, recarregue a página. <br />Código: #001")));
            }
            $tempArry = parent::ExecutarSQL($SQL,"iRunSQLSecundario22");
            $Formatado = array();
            $esp = $this->consulta->getEspacialidade();
            switch (strtolower($esp)) {
                case Consulta::$ESP_MUNICIPAL:
                    if(!$this->varOnly)
                        foreach($tempArry as $key=>$val){
                            $arg = $val;
                            unset($arg['im']);
                            unset($arg['nome']);
                            unset($arg['uf']);
                            if(in_array($arg["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }else{
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            if($this->isSearchName){
                                $Formatado[$val['im']]["nome"] = $val["nome"];
                                if(isset($val["uf"]))
                                    $Formatado[$val['im']]["uf"] = $val["uf"];
                            }
                            $Formatado[$val['im']]["id"] = $val["im"];
                            $Formatado[$val['im']]["esp"] = $esp;
                            $Formatado[$val['im']]["vs"][$val["iv"]."_".$val["ka"]] = $arg;
                        }
                    else
                        foreach($tempArry as $key=>$val){
                            if(in_array($val["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],2,'.','');
                            }else{
                                $val["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            $Formatado[$key] = $val;
                        }
                    break;
                case Consulta::$ESP_ESTADUAL:
                    if(!$this->varOnly)
                        foreach($tempArry as $key=>$val){
                            $arg = $val;
                            unset($arg['im']);
                            unset($arg['nome']);
                            unset($arg['u']);
                            if(in_array($arg["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }else{
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            
                            if($this->isSearchName)
                                $Formatado[$val['im']]["nome"] = $val["nome"];
                            
                            $Formatado[$val['im']]["u"] = $val["u"];
                            $Formatado[$val['im']]["id"] = str_replace("10000", "", $val["im"]);
                            $Formatado[$val['im']]["esp"] = $esp;
                            $Formatado[$val['im']]["vs"][$val["iv"]."_".$val["ka"]] = $arg;
                        }
                    else
                        foreach($tempArry as $key=>$val){
                            if(in_array($val["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],2,'.','');
                            }else{
                                $val["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            $Formatado[$key] = $val;
                        }
                    break;
                case Consulta::$ESP_REGIAODEINTERESSE:
                    if(!$this->varOnly)
                        foreach($tempArry as $key=>$val){
                            $arg = $val;
                            unset($arg['im']);
                            unset($arg['nome']);
                            unset($arg['uf']);
                            unset($arg['is_ri']);
                            if(in_array($arg["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }else{
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            $Formatado[$val['im']]["nome"] = $val["nome"];
                            $Formatado[$val['im']]["uf"] = $val["uf"];
//                            if($this->isSearchName){
//                            }
                            $Formatado[$val['im']]["id"] = $val["im"];
                            $Formatado[$val['im']]["is_ri"] = $val["is_ri"];
                            $Formatado[$val['im']]["esp"] = $esp;
                            $Formatado[$val['im']]["vs"][$val["iv"]."_".$val["ka"]] = $arg;
                        }
                    else
                        foreach($tempArry as $key=>$val){
                            if(in_array($val["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],2,'.','');
                            }else{
                                $val["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            $Formatado[$key] = $val;
                        }
                    break;
                case Consulta::$ESP_PAIS:
                    $this->varOnly = false;
                    if(!$this->varOnly)
                        foreach($tempArry as $key=>$val){
                            $arg = $val;
                            unset($arg['im']);
                            unset($arg['nome']);
                            unset($arg['country']);
                            if(in_array($arg["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }else{
                                $arg["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            
                            if($this->isSearchName)
                                $Formatado[$val['im']]["nome"] = $val["nome"];
                            
                            $Formatado[$val['im']]["country"] = $val["country"];
                            $Formatado[$val['im']]["id"] = str_replace("10000", "", $val["im"]);
                            $Formatado[$val['im']]["esp"] = $esp;
                            $Formatado[$val['im']]["vs"][$val["iv"]."_".$val["ka"]] = $arg;
                        }
                    else
                        foreach($tempArry as $key=>$val){
                            if(in_array($val["iv"],  PublicMethods::$ArrayPadding3ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],3,'.','');
                            }elseif(in_array($val["iv"],  PublicMethods::$ArrayPadding2ZerosDireita)){
                                $val["v"] = cutNumber($val["v"],2,'.','');
                            }else{
                                $val["v"] = cutNumber($arg["v"],2,'.','');
                            }
                            $Formatado[$key] = $val;
                        }
                    break;
                
                /*=========================================================
                 * Novas Espacialidades aqui!
                 *=========================================================*/
            }
            $this->results = $Formatado;
        }
        
        private function getSelect(){
            switch ($this->consulta->getEspacialidade()) {
                case Consulta::$ESP_MUNICIPAL:
                    if(!$this->varOnly){
                        if(!$this->isSearchName)
                            $ParteInicialSQL = "SELECT valor as v, fk_municipio as im,fk_ano_referencia as ka,fk_variavel as iv FROM valor_variavel_mun as vv
                                                INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                                                INNER JOIN estado as e ON (e.id = m.fk_estado)
                                                WHERE fk_municipio IN ";
                        else
                            $ParteInicialSQL = "SELECT valor as v, fk_municipio as im,fk_ano_referencia as ka,fk_variavel as iv, m.nome as nome FROM valor_variavel_mun as vv
                                                INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                                                INNER JOIN estado as e ON (e.id = m.fk_estado)
                                                WHERE fk_municipio IN ";
                    }
                    else
                        $ParteInicialSQL = "SELECT valor as v, fk_municipio as im, fk_variavel as iv FROM valor_variavel_mun as vv
                                            INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                                            INNER JOIN estado as e ON (e.id = m.fk_estado)
                                            WHERE fk_municipio IN  ";
                    break;
                case Consulta::$ESP_ESTADUAL:
                    if(!$this->varOnly)
                        if(!$this->isSearchName)
                            $ParteInicialSQL = "SELECT valor as v, fk_estado::text||'e' as im,fk_ano_referencia as ka,fk_variavel as iv, e.uf as u FROM valor_variavel_estado as vv
                                                INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                                INNER JOIN estado as e ON (e.id = vv.fk_estado)
                                                INNER JOIN regiao as r ON (r.id = e.fk_regiao)
                                                WHERE fk_estado IN ";
                        else
                            $ParteInicialSQL = "SELECT valor as v, fk_estado::text||'e' as im,fk_ano_referencia as ka,fk_variavel as iv, e.uf as u,e.nome as nome FROM valor_variavel_estado as vv
                                                INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                                INNER JOIN estado as e ON (e.id = vv.fk_estado)
                                                INNER JOIN regiao as r ON (r.id = e.fk_regiao)
                                                WHERE fk_estado IN ";
                    else
                        $ParteInicialSQL = "SELECT valor as v, fk_estado::text||'e' as im,fk_variavel as iv, e.uf as u FROM valor_variavel_estado as vv
                                            INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                            INNER JOIN estado as e ON (e.id = vv.fk_estado)
                                            INNER JOIN regiao as r ON (r.id = e.fk_regiao)
                                            WHERE fk_estado IN ";
                    break;
                case Consulta::$ESP_REGIAODEINTERESSE:
                    if(!$this->varOnly)
                        $ParteInicialSQL = "SELECT valor as v, vv.fk_municipio::text||'i' as im,fk_ano_referencia as ka,fk_variavel as iv,m.nome as nome,e.uf as uf, ri.id as is_ri FROM valor_variavel_mun as vv
                                            INNER JOIN regiao_interesse_has_municipio as b ON (b.fk_municipio = vv.fk_municipio)
                                            INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                                            INNER JOIN estado as e ON (e.id = m.fk_estado)
                                            INNER JOIN regiao_interesse as ri ON (b.fk_regiao_interesse = ri.id)
                                            WHERE fk_regiao_interesse IN ";
                    else
                        $ParteInicialSQL = "SELECT valor as v, vv.fk_municipio::text||'i' as im, fk_variavel as iv, m.nome as nome,e.uf as uf, ri.id as is_ri FROM valor_variavel_mun as vv
                                            INNER JOIN regiao_interesse_has_municipio as b ON (b.fk_municipio = vv.fk_municipio)
                                            INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                                            INNER JOIN estado as e ON (e.id = m.fk_estado)
                                            INNER JOIN regiao_interesse as ri ON (b.fk_regiao_interesse = ri.id)
                                            WHERE fk_regiao_interesse IN ";
                    break;
                case Consulta::$ESP_PAIS:
                    $this->varOnly = false;
                    $this->isSearchName = true;
                    if(!$this->varOnly){
                        if(!$this->isSearchName)
                            $ParteInicialSQL = "SELECT valor as v, fk_pais::text||'p' as im,fk_ano_referencia as ka,fk_variavel as iv FROM valor_variavel_pais as vv
                                                INNER JOIN pais as m ON (vv.fk_pais = m.id)
                                                WHERE fk_pais IN ";
                        else
                            $ParteInicialSQL = "SELECT valor as v, fk_pais::text||'p' as im,fk_ano_referencia as ka,fk_variavel as iv, m.nome_pais as nome,'true' as country FROM valor_variavel_pais as vv
                                                INNER JOIN pais as m ON (vv.fk_pais = m.id)
                                                WHERE fk_pais IN ";
                    }
                    else
                        $ParteInicialSQL = "SELECT valor as v, fk_pais::text||'p' as im,fk_variavel as iv FROM valor_variavel_pais as vv
                                            INNER JOIN pais as m ON (vv.fk_pais = m.id)
                                            WHERE fk_pais IN ";
                    break;
                /*=========================================================
                 * Novas Espacialidades aqui!
                 *=========================================================*/
            }
            
            return $ParteInicialSQL;
        }
        
        private function generateSequence($start, $end){
            
        }
        
        private function getSelectFiltroMain(){
            $Filtros = $this->consulta->getFiltros();
            for($x = 0; $x < count($Filtros);$x++){
                switch ($Filtros[$x]->getFiltro()){
                    case Filtro::$FILTRO_MUNICIPIO:
                        $filtros = $Filtros[$x]->getValores();
//                        if(count($filtros)>3000){
//                            $comp = array();
//                            $compB = array();
//                            for($x = 1; $x <= 5565;$x++){
//                                $comp[] = $x;
//                            }
//                            foreach($filtros as $val){
//                                $compB[] = $val->getNome();
//                            }
//                            $result = array_diff($compB, $comp);
//                            foreach($result as $val){
//                                $SQLRegiao[] = "(m.id <> $val)";
//                            }
//                            if(count($result) == 0){
//                                $SQLRegiao[] = "(1 = 1)";
//                            }
//                        }else{
                        foreach($Filtros[$x]->getValores() as $val){
                            $SQLRegiao[] = "{$val->getNome()}";
                        }
//                        }
                        break;
                    case Filtro::$FILTRO_REGIAO:
                        foreach($Filtros[$x]->getValores() as $val){
                            $SQLRegiao[] = "{$val->getNome()}";
                        }
                        break;
                    case Filtro::$FILTRO_ESTADO:
                        foreach($Filtros[$x]->getValores() as $val){
                            $SQLRegiao[] = "{$val->getNome()}";
                        }
                        break;
                    case Filtro::$FILTRO_REGIAODEINTERESSE:
                        foreach($Filtros[$x]->getValores() as $val){
                            $SQLRegiao[] = "{$val->getNome()}";
                        }
                        break;
                    case Filtro::$FILTRO_PAIS:
                        foreach($Filtros[$x]->getValores() as $val){
                            $SQLRegiao[] = "{$val->getNome()}";
                        }
                        break;
                /*=========================================================
                 * Novas Espacialidades aqui!
                 *=========================================================*/
                }
            }
            if(is_array($SQLRegiao))
                $SQL1 = implode(',',$SQLRegiao);
            
            return $SQL1;
        }
        
        //======================================================================
        //Getters and Setters
        //======================================================================
        
        public function getSQL($IdVariavel,$AnoVariavel){
            return $this->getSQLMain($IdVariavel,$AnoVariavel);
        }
        
        public function getSQLSecundario($ArrayId){
            return $this->iGetSQLSecundario($ArrayId);
        }
        
        /**
         * Pega o retorno do SQL
         * @param string $SQL recebe a sql e executa
         * @example <br />array(1) {
         *   <br />[2365]=>
         *   <br />array(2) {
         *     <br />["nome"]=>
         *     <br />string(19) "ABADIA DOS DOURADOS"
         *     <br />[0]=>
         *     <br />array(3) {
         *       <br />["valor"]=>
         *       <br />string(6) "72.936"
         *       <br />["fk_ano_referencia"]=>
         *       <br />string(1) "3"
         *       <br />["fk_variavel"]=>
         *       <br />string(1) "1"
         *     <br />}
         *   <br />}
         */
        public function getSQLResults($SQL){
            return $this->iRunSQL($SQL);
        }
        
        public function getSQLResultsSecundario($ResultadosMain,$SQL){
            return $this->iRunSQLSecundario($ResultadosMain,$SQL);
        }
        
        public function getSQLFiltroMain(){
            return $this->getSelectFiltroMain();
        }
    }
?>
