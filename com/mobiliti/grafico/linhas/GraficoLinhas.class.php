<?php
    /**
     * Description of GraficoLinhas
     *
     * @author Valter Lorran
     */
    class GraficoLinhas {
        
        private $dados = array();
        private $eixo;
        private $bd;
        public static $Rows = array();


        public function __construct($lugares, $indicador, $espacialidade, $ano) {
//            echo '__construct<br />';
            $this->bd = new bd();
            $this->consultar($lugares, $indicador, $espacialidade, $ano);
        }
        
        public function draw(){
            echo json_encode($this->dados);
        }
        
        private function consultar($lugares, $indicador, $espacialidade, $ano){
//            echo 'consultar<br />';
            $ParteInicialSQL = "";
            $filtro = "";
            switch ($espacialidade) {
                 case Consulta::$ESP_MUNICIPAL:
                    $ParteInicialSQL = "SELECT valor as v, fk_municipio as im,fk_ano_referencia as ka,fk_variavel as iv, label_ano_referencia as a 
                                        FROM ano_referencia as ar, valor_variavel_mun as vv  
                                        WHERE fk_municipio IN ";
                     $filtro = "fk_municipio";
                     
                     $SQLNome = "SELECT nome, id FROM municipio WHERE id IN (".implode(',',$lugares).")";
                    break;
                case Consulta::$ESP_ESTADUAL:
//                    echo 'Estadual';
                    $ParteInicialSQL = "SELECT valor as v, fk_estado as im, fk_ano_referencia as ka, fk_variavel as iv, label_ano_referencia as a 
                                        FROM ano_referencia as ar, valor_variavel_estado as vv
                                        INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                        INNER JOIN estado as e ON (e.id = vv.fk_estado)
                                        INNER JOIN regiao as r ON (r.id = e.fk_regiao)
                                        WHERE fk_estado IN ";
                     $filtro = "fk_estado";
                     
                     $SQLNome = "SELECT nome, id FROM estado WHERE id IN (".implode(',',$lugares).")";
                    break;
                case Consulta::$ESP_REGIAODEINTERESSE:
                    $arr = array();
                    foreach($lugares as $key=>$val){
    //                    echo '$val[id]: '.$val.'<br />';
                        $arr[] = $val;
                    }
                    
                    $arr2 = array();
                    $tam_lug2 = count($lugares);
                    for($i = 0; $i < $tam_lug2; $i++){
//                        echo 'Lugares: '.$lugares[$i].'  ';
                        $arr2[] = $lugares[$i];
                    }
                    
                    $whereLugs2 = '('.implode(',',$arr2).')';

                    $RI = "SELECT Distinct m.id as id
                            FROM valor_variavel_mun as vv
                            INNER JOIN regiao_interesse_has_municipio as b ON (b.fk_municipio = vv.fk_municipio)
                            INNER JOIN municipio as m ON (vv.fk_municipio = m.id)
                            INNER JOIN estado as e ON (e.id = m.fk_estado)
                            INNER JOIN regiao_interesse as ri ON (b.fk_regiao_interesse = ri.id)
                            WHERE fk_regiao_interesse IN ".$whereLugs2;
                    $RI .=  " ORDER BY m.id";    
                    
                    //echo $RI;
                    $Resp = pg_query($this->bd->getConexaoLink(), $RI) or die ("Nao foi possivel executar a consulta! ");
                    $Linha = pg_fetch_assoc($Resp);
                    $cmdtuples = pg_affected_rows($Resp);
//                    echo $cmdtuples . " tuples are affected.\n";
                    $lugares = array();
                    $i = 0;
                    
                    while ($Linha = pg_fetch_assoc($Resp))
                    {
//                        echo 'While  ';
//                        echo 'Linha: '.$linha.'  ';
                        $lugares[$i] = $Linha['id'];
//                        echo $lugares[$i];
                        $i++;
                    }
                    
                    $ParteInicialSQL = "SELECT valor as v, fk_municipio, fk_ano_referencia as ka,fk_variavel as iv, m.nome as nome, e.uf as uf, ar.label_ano_referencia as a 
                                        FROM ano_referencia as ar, valor_variavel_mun as vv
                                        INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                        INNER JOIN municipio as m ON (m.id = vv.fk_municipio)
                                        INNER JOIN estado as e ON (e.id = m.fk_estado)
                                        INNER JOIN regiao as r ON (r.id = e.fk_regiao)
                                        WHERE fk_municipio IN ";
                     $filtro = "fk_municipio";
                     
                     $SQLNome = "SELECT nome, id FROM municipio WHERE id IN (".implode(',',$lugares).")";
//                     echo $SQLNome;
                    break;
                case Consulta::$ESP_UDH:
                    $ParteInicialSQL = "SELECT valor as v, fk_udh,fk_ano_referencia,fk_variavel, udh.nome FROM valor_variavel_udh as vv
                                        INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                        INNER JOIN udh ON (udh.id = vv.fk_udh)
                                        WHERE ";
                     $filtro = "fk_udh";
                    break;
                case Consulta::$ESP_REGIAOMETROPOLITANA:
                    $ParteInicialSQL = "SELECT valor as v, fk_rm,fk_ano_referencia,fk_variavel, rm.nome FROM valor_variavel_rm as vv
                                        INNER JOIN variavel as v ON (vv.fk_variavel = v.id)
                                        INNER JOIN rm ON (rm.id = vv.fk_rm)
                                        WHERE ";
                    $filtro = "fk_rm";
                    break;
            }
            
            if(isset($lugares)){
                $arr = array();
//                echo 'indicador: '.$indicador;
                $lugs = array();
                $tam_lug = count($lugares);
//                echo 'tam_lug: '.$tam_lug.'  ';
                for($i = 0; $i < $tam_lug; $i++){
//                    echo $lugares[$i];
                    $lugs[] = $lugares[$i];
    //                echo '  '.$lugs[$i];
                }
                $whereLugs = '('.implode(',',$lugs).')';
//                foreach($lugares as $key=>$val){
//                    echo '$val[id]: '.$val.'<br />';
//                    $arr[] = $val;
//                }
                
                $SQL = "";
//                $SQL = $ParteInicialSQL . " (".implode(",", $arr).") AND (fk_variavel = $indicador) AND(ar.id = fk_ano_referencia)  ORDER BY fk_ano_referencia";
                $SQL = $ParteInicialSQL.$whereLugs." AND (fk_variavel = $indicador) AND(ar.id = fk_ano_referencia)  ORDER BY fk_ano_referencia";
//                echo 'SQL: '.$SQL.'<br />';
                $result = pg_query($this->bd->getConexaoLink(), $SQL) or die ("Nao foi possivel executar a consulta! ");
//                echo 'result: '.$result.'<br />';
//                foreach($result as $val){
//                    $temp = $val;
//                    echo 'temp: '.$temp.'<br />';
//                    echo 'val[im]: '.$val['im']['im'].'<br />';
//                    
//                    unset($temp["im"]);
//                    unset($temp["n"]);
//                    GraficoLinhas::$Rows[$val['im']]['im'] = $val['im'];
//                    echo GraficoLinhas::$Rows[$val['im']]['im'].'<br />';
//                    GraficoLinhas::$Rows[$val['im']]['vs'][$val['ka']] = $temp;
//                    echo GraficoLinhas::$Rows[$val['im']]['vs'][$val['ka']].'<br />';
//                }
                
                $tam_lug = count($lugares);
                $j = 0;
                $i = 0;
                while($Linha = pg_fetch_assoc($result)){
                    $this->dados['ano'][] = $Linha['a'];
                    $this->dados['valor'][] = $Linha['v'];
//                    echo 'Linha[a]: '.$Linha['a'].'<br />';
//                    echo 'Linha[v]: '.$Linha['v'].'<br />';
                }
                
//                $SQL = "SELECT nome, id FROM estado WHERE id IN (".implode(',',$lugares).")";
//                echo $SQLNome;
                $Resposta = pg_query($this->bd->getConexaoLink(), $SQLNome) or die ("Nao foi possivel executar a consulta! ");
                
                while ($Linha = pg_fetch_assoc($Resposta)){
//                    echo $Linha['nome'];
                    $this->dados['nome'][] = $Linha['nome'];
                }
            }
        }
    }

?>
