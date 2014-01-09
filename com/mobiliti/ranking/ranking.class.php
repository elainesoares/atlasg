<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ranking
 *
 * @author Lorran
 */
function cutNumber($num, $casas_decimais, $decimal = ',', $milhar = '.') {
//        $num = str_replace(".", $decimal, $num);
    $ex = explode('.', $num);
    if (strlen($ex[1]) >= $casas_decimais) {
	return substr($num, 0, strpos($num, '.') + $casas_decimais + 1);
    } else {
	$con = "";
	for ($diff = $casas_decimais - strlen($ex[1]); $diff > 0; $diff--) {
	    $con .= "0";
	}
	return $num . $con;
    }
}

function download_send_headers($filename) {
    header('Content-Type: text/html; charset=utf-8');
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

class Raking {

    const vB = 0.500;
    const vC = 0.600;
    const vD = 0.700;
    const vE = 0.800;
    const nA1 = "IDHM Muito Baixo";
    const nA = "IDHM Baixo";
    const nB = "IDHM Médio";
    const nC = "IDHM Alto";
    const nD = "IDHM Muito Alto";

    private $sql_proto = array(
	"municipal" => "SELECT valor as v, valor_variavel_mun.fk_municipio as i, m.nome as n, e.uf (TBS) FROM valor_variavel_mun
                          INNER JOIN municipio as m ON (fk_municipio = m.id)
                          INNER JOIN estado as e ON (e.id = m.fk_estado)
                          INNER JOIN rank as r ON (r.fk_municipio = m.id)
                          WHERE fk_variavel IN (VARS) and valor_variavel_mun.fk_ano_referencia = (ANO) and r.fk_ano_referencia = (ANO) (MOREWHERE)",
	"estadual" => "SELECT valor as v, valor_variavel_estado.fk_estado as i, m.nome as n (TBS) FROM valor_variavel_estado
                          INNER JOIN estado as m ON (fk_estado = m.id)
                          INNER JOIN rank_estado as r ON (r.fk_estado = m.id)
                          WHERE fk_variavel IN (VARS) and valor_variavel_estado.fk_ano_referencia = (ANO) and r.fk_ano_referencia = (ANO)",
	"count_estadual" => "SELECT count(*) FROM valor_variavel_estado
                          INNER JOIN estado as m ON (fk_estado = m.id)
                          WHERE fk_variavel IN (VARS) and fk_ano_referencia = (ANO) ",
	"count_municipal" => "SELECT count(*) FROM valor_variavel_mun
                          WHERE fk_variavel IN (VARS) and fk_ano_referencia = (ANO) "
    );
    private $sql_f = array(
	"municipal" => "SELECT valor as v, fk_municipio as i, fk_variavel as k FROM valor_variavel_mun
                          INNER JOIN municipio as m ON (fk_municipio = m.id)
                          WHERE fk_variavel IN (VARS) and fk_ano_referencia = (ANO)",
	"estadual" => "SELECT valor as v, fk_estado as i, fk_variavel as k FROM valor_variavel_estado
                          INNER JOIN estado as m ON (fk_estado = m.id)
                          WHERE fk_variavel IN (VARS) and fk_ano_referencia = (ANO)"
    );
    private $id_indics = array(INDICADOR_IDH, INDICADOR_RENDA, INDICADOR_LONGEVIDADE, INDICADOR_EDUCACAO);
    private $data = array();
    public $pOrdem_id;
    public $pOrdem;
    public $pLimit;
    public $pEspc;
    public $pPag;
    public $pEstado;
    public $Paginas;
    public $pStart;
    public $nOrdem;
    public $estados;
    public $showBtn;
    public $nomeEstado = "";
    public $fkAno = 3;

    public function __construct($ordem_id = null, $ordem = null, $pag = null, $espc = null, $start = null, $estado = null, $estados_pos = null, $load_more = false, $download = false,$ano = 3) {
        $limit = 100;
	$this->showBtn = $load_more;
	if ($load_more) {
	    $limit = 6000;
	}
	if (is_null($ordem_id))
	    $ordem_id = INDICADOR_IDH;
	if (is_null($ordem))
	    $ordem = "asc";
	if (is_null($limit))
	    $limit = 100;
	if (is_null($espc))
	    $espc = "municipal";
	if (is_null($pag))
	    $pag = 1;
	if (is_null($start) || $start < 0)
	    $start = 1;
	if (is_null($estado))
	    $estado = 0;
	if ($ordem != "desc" && $ordem != "asc") {
	    $ordem = "asc";
	}
        $this->fkAno =(int) $ano;
        if($this->fkAno == 0) $this->fkAno = 3;
//        if($ordem == "desc")$ordem = 'asc';
//        else $ordem = 'desc';
	$this->pOrdem_id = (int) $ordem_id;
	$this->pOrdem = $ordem;
	$this->pLimit = (int) $limit;
	$this->pEspc = $espc;
	$this->pPag = (int) $pag;
	$this->pStart = (int) $start;
	$this->pEstado = (int) $estado;

	$bd = new bd();
	$rankingBy = "";
	switch ($ordem_id) {
	    case INDICADOR_IDH:
		$this->nOrdem = "IDHM";
		$rankingBy = "posicao_idh";
		break;
	    case INDICADOR_RENDA:
		$this->nOrdem = "IDHM<br />Renda";
		$rankingBy = "posicao_idhr";
		break;
	    case INDICADOR_LONGEVIDADE:
		$this->nOrdem = "IDHM Longevidade";
		$rankingBy = "posicao_idhl";
		break;
	    case INDICADOR_EDUCACAO:
		$this->nOrdem = "IDHM Educação";
		$rankingBy = "posicao_idhe";
		break;
	    default:
		break;
	}

	$sql_seletor = str_replace("VARS", $ordem_id, $this->sql_proto[$espc]) . " ORDER BY ot $ordem LIMIT $limit offset " . (($pag - 1) * $limit);
//            $sql_count =  str_replace("VARS", $ordem_id, $this->sql_proto["count_$espc"]);

	    $sql_seletor = str_replace("(ANO)", $this->fkAno, $sql_seletor);
	if ($this->pEstado > 0 && $espc == "municipal") {
	    $sql_seletor = str_replace("(MOREWHERE)", " AND e.id = {$this->pEstado} ", $sql_seletor);
	    $rankingBy = str_replace("_", "_e_", $rankingBy);
	} else {
	    $sql_seletor = str_replace("(MOREWHERE)", "", $sql_seletor);
	}
	$sql_seletor = str_replace("(TBS)", ",$rankingBy as ot", $sql_seletor);
//            $count = $bd->ExecutarSQL($sql_count,"count");
//            $this->Paginas = ceil(($count[0]["count"])/$limit);
//        echo($sql_seletor);
	$res = $bd->ExecutarSQL($sql_seletor, "frist");
	$ids = array();
	if ($espc == "municipal") {
	    foreach ($res as $key => $val) {
		$this->data[$val["i"]] = array(
		    "n" => "{$val["n"]} ({$val["uf"]})",
		    "ot" => $val["ot"],
		    "vs" => array(
			$ordem_id => array(
			    "v" => $val["v"],
			    "k" => $ordem_id
			)
		    )
		);
		$ids[] = $val["i"];
	    }
	    $SQL_e = "SELECT nome, id FROM estado order by nome";
	    $resp = $bd->ExecutarSQL($SQL_e, "estado 22");
	    $this->estados = $resp;
	} elseif ($espc == "estadual") {
	    $counter = 0;
	    $last = 0;
	    $hidden_array = array();
	    $arr = explode(",", $estados_pos);
	    $t = count($arr) - 1;
	    foreach ($res as $key => $val) {
		if ($ordem == "desc") {
		    if ($last != cutNumber($val["v"], 3, '.'))
			$counter++;
		    $last = cutNumber($val["v"], 3, '.');
		}if ($ordem == "asc") {
		    $counter = $arr[$t];
		    $t--;
		}
		$hidden_array[] = $counter;
		$this->data[$val["i"]] = array(
		    "ot" => $val["ot"],
		    "n" => "{$val["n"]}",
		    "vs" => array(
			$ordem_id => array(
			    "v" => $val["v"],
			    "k" => $ordem_id
			)
		    )
		);
		$ids[] = $val["i"];
	    }
	    echo "<input type='hidden' value='" . join(",", $hidden_array) . "' id='holderRankEstados' />";
	}
	$places = implode(",", $ids);

	$vars = implode(",", array_diff($this->id_indics, array($ordem_id)));

	$var_name = "";
	if ($espc == "municipal")
	    $var_name = "fk_municipio";
	elseif ($espc == "estadual") {
	    $var_name = "fk_estado";
	}
        $this->sql_f[$espc] = str_replace("(ANO)", $this->fkAno, $this->sql_f[$espc]);
	$sql_follower = str_replace("VARS", $vars, $this->sql_f[$espc]) . " and $var_name IN ($places) order by fk_variavel";
	$res_f = $bd->ExecutarSQL($sql_follower);
	foreach ($res_f as $key => $val) {
	    $this->data[$val["i"]]["vs"][$val["k"]] = array(
		"v" => $val["v"],
		"k" => $val["k"]
	    );
	}
//            echo "<pre>";
//            var_dump($this->data);die();
//            foreach($this->data as $key=>$val){
//                ksort($this->data[$key]["vs"]);
//            }
	if ($download) {
	    ob_clean();
            $n = $this->getNomeIndicador($ordem_id);
            $n = str_replace(" ", "_", $n);
            if($espc == "municipal"){
                if($estado == 0)
                    download_send_headers("AtlasIDHM2013_RankingMunicipal_".$n."_".convertAnoIDtoLabel($this->fkAno)."_Brasil.csv");
                else{
                    $n2 = $this->getEstadoNome();
                    $n2 = str_replace(" ", "_", $n2);
                    download_send_headers("AtlasIDHM2013_RankingMunicipal_".$n."_".convertAnoIDtoLabel($this->fkAno)."_$n2.csv");
                }
            }
            else if($espc == "estadual"){
                download_send_headers("AtlasIDHM2013_RankingEstadual_".convertAnoIDtoLabel($this->fkAno).".csv");
            }

	    foreach ($this->data as $key => $val) {
		$c = 0;
		echo "sep = ,\n";
		echo utf8_decode("Posição º,Nome,");
		foreach ($val["vs"] as $k => $v) {
		    $or = "desc";
		    $class = "";
		    $class_ds = "";
		    switch ($v["k"]) {
			case INDICADOR_IDH:
			    echo "IDHM (".convertAnoIDtoLabel($this->fkAno).")";
			    break;
			case INDICADOR_RENDA:
			    echo "IDHM Renda (".convertAnoIDtoLabel($this->fkAno).")";
			    break;
			case INDICADOR_LONGEVIDADE:
			    echo "IDHM Longevidade (".convertAnoIDtoLabel($this->fkAno).")";
			    break;
			case INDICADOR_EDUCACAO:
			    echo utf8_decode("IDHM Educação")." (".convertAnoIDtoLabel($this->fkAno).")";
			    break;
			default:
			    break;
		    }
		    if($c <= 2)
			echo ",";
		    $c++;
		}
		break;
	    }
	    echo "\n";
	    $ts = false;
	    $counter = 0;
	    $last = "0";
	    $answer = "";
	    $j = 0;
	    foreach ($this->data as $key => $val) {
		$n = (float) cutNumber($val["vs"][INDICADOR_IDH]['v'], 3, '.');
		if (cutNumber($val["vs"][$this->pOrdem_id]['v'], 3, '.', '') != $last) {
		    $counter++;
		}
		$answer.= "{$val["ot"]} º,";
		$answer.= "{$val["n"]},";
		$c = 0;
		foreach ($val["vs"] as $k => $v) {
		    $val["vs"][$k]["v"] = cutNumber($v["v"], 3, ',', '');
		    if($c <= 2)
			$answer.= $val["vs"][$k]["v"].",";
		    else
			$answer.= $val["vs"][$k]["v"];
		    $c++;
		}
		$last = $val["vs"][$this->pOrdem_id]['v'];
		$answer.= "\n";
//                echo $val["vs"][$this->pOrdem_id]['v']." - ".$last . "<br />";
		$ts = !$ts;
		$j++;
	    }
	    echo utf8_decode($answer);
	    if (isset($_POST["cross_data_download"])) {
		$_POST["cross_data_download"] = false;
	    }
	    die();
	}
    }

    public function getEstadoNome() {
	foreach ($this->estados as $key => $val) {
	    if ($this->pEstado == $val["id"])
		return $val["nome"];
	}
	return "Brasil";
    }

    public function draw() {
	$ts = false;
	$counter = 0;
	$last = "0";
	$answer = "";
	$j = 0;
	foreach ($this->data as $key => $val) {
	    $label = "";
	    $class = "bolinhaRank ";
	    $n = (float) cutNumber($val["vs"][INDICADOR_IDH]['v'], 3, '.');
	    if ($n < Raking::vB) {
		$class.="bolinhaMuitoRuim";
		$label = Raking::nA1;
	    } elseif ($n < Raking::vC) {
		$class.="bolinhaRuim";
		$label = Raking::nA;
	    } elseif ($n < Raking::vD) {
		$class.="bolinhaMedia";
		$label = Raking::nB;
	    } elseif(($n < Raking::vE)) {
		$class.="bolinhaBom";
		$label = Raking::nC;
	    }else{
		$class.="bolinhaOtimo";
		$label = Raking::nD;
            }
	    if (cutNumber($val["vs"][$this->pOrdem_id]['v'], 3, '.', '') != $last) {
		$counter++;
	    }
//                if($ts)
//                    $answer.= "<tr class='trGray hoverTrRank'>";
//                else
//                    $answer.= "<tr class='hoverTrRank'>";
	    echo "<tr class='hoverTrRank'>";
	    $answer.= "<td class='numRank'>{$val["ot"]} º</td>";
	    $answer.= "<td class='rankLugar'>{$val["n"]}</td>";
	    $c = 0;
	    foreach ($val["vs"] as $k => $v) {
		$val["vs"][$k]["v"] = cutNumber($v["v"], 3, ',', '');
                if($k == INDICADOR_IDH)
                    $answer.= "<td class='cell_rank td_rank_cell _$c'>" . $val["vs"][$k]["v"] . "<div class='$class float-right' data-original-title='$label' title data-placement='bottom'></div></td>";
                else
                    $answer.= "<td class='cell_rank td_rank_cell _$c'>" . $val["vs"][$k]["v"] . "</td>";
		$c++;
	    }
	    $last = $val["vs"][$this->pOrdem_id]['v'];
	    $answer.= "</tr>";
//                echo $val["vs"][$this->pOrdem_id]['v']." - ".$last . "<br />";
	    $ts = !$ts;
	    $j++;
	}
	$this->pStart = $counter;
	echo "<table class='rank_table'>";
	echo "<thead><th class='numRank padding-10px-bottom'>Posição</th><th class='rankLugar'>Lugares</th>";
	$or = "asc";
	foreach ($this->data as $key => $val) {
	    $c = 0;
	    foreach ($val["vs"] as $k => $v) {
		$or = "asc";
		$class = "";
		$class_ds = "";
		if ($v["k"] == $this->pOrdem_id) {
		    if ($this->pOrdem == "asc") {
			$or = "desc";
			$class_ds = "destaqueRank1";
			$class = "rank_arrow rank_arrow_down";
		    } else {
			$class_ds = "destaqueRank1";
			$class = "rank_arrow rank_arrow_up";
		    }
		} else {
//                        $class = "rank_arrow rank_arrow_display";
		}
		switch ($v["k"]) {
		    case INDICADOR_IDH:
			echo "<th onclick=\"sendData({$v["k"]},'$or',{$this->pPag},'{$this->pEspc}',{$this->pStart},{$this->pEstado})\" class='indicRank'><div class='$class' data-original-title='Ordenar' data-placement='bottom' ></div><div style='clear: both'></div><div class='nameIndcRank idh-td-rank $class_ds j_$c'>IDHM</div></th>";
			break;
		    case INDICADOR_RENDA:
			echo "<th onclick=\"sendData({$v["k"]},'$or',{$this->pPag},'{$this->pEspc}',{$this->pStart},{$this->pEstado})\" class='indicRank'><div class='$class' data-original-title='Ordenar' data-placement='bottom'></div><div style='clear: both'></div><div class='nameIndcRank $class_ds j_$c'>IDHM<br />Renda</div></th>";
			break;
		    case INDICADOR_LONGEVIDADE:
			echo "<th onclick=\"sendData({$v["k"]},'$or',{$this->pPag},'{$this->pEspc}',{$this->pStart},{$this->pEstado})\" class='indicRank'><div class='$class' data-original-title='Ordenar' data-placement='bottom'></div><div style='clear: both'></div><div class='nameIndcRank $class_ds j_$c'>IDHM Longevidade</div></th>";
			break;
		    case INDICADOR_EDUCACAO:
			echo "<th onclick=\"sendData({$v["k"]},'$or',{$this->pPag},'{$this->pEspc}',{$this->pStart},{$this->pEstado})\" class='indicRank'><div class='$class' data-original-title='Ordenar' data-placement='bottom'></div><div style='clear: both'></div><div class='nameIndcRank $class_ds j_$c'>IDHM Educação</div></th>";
			break;
		    default:
			break;
		}
		$c++;
	    }
	    break;
	}
	echo "</thead>";
	echo $answer;
	if (!$this->showBtn) {
	    if ($j > 99)
		echo "<tr id='tr_load_more'><td colspan='100%'><a class='button-carregar-mais' style='float:right' type='button'>Exibir todos os resultados</a></td></tr>";
	}else {
	    ?>
	    <script>
	        $('html, body').animate({
	    	scrollTop: $("tr:eq(100)").offset().top - 300
	        }, 300); 
	    </script>
	    <?
	    if ($j > 99)
		echo "<tr id='tr_load_more'><td colspan='100%'><a style='float:right;cursor:pointer' type='button' onclick='javascript:$(\"html,body\").scrollTop(0)'>Retornar ao topo</a></td></tr>";
	}
	echo "</table>";
//            $this->drawButtons();
    }

    
    public function getNomeIndicador($id){
        switch ($id) {
            case INDICADOR_IDH:
                return "IDHM";
            case INDICADOR_RENDA:
                return "IDHM Renda";
            case INDICADOR_LONGEVIDADE:
                return "IDHM Longevidade";
            case INDICADOR_EDUCACAO:
                return "IDHM Educação";
            default:
                return null;
        }
    }
    public function drawSelect() {
//            $arr = array(50,100,500,1000,2000,'Todos');
//            if($this->pEspc == "estadual") 
//                echo "<select style='display:none' id=\"selectRankLimit\">";
//            else
//                echo "Exibir: <select id=\"selectRankLimit\">";
//            foreach($arr as $key=>$val){
//                if($val == "Todos"){
//                    if($this->pLimit == 10000)
//                        echo "<option selected=\"selected\" value='10000'>$val</option>";
//                    else
//                        echo "<option value='10000'>$val</option>";
//                    continue;
//                }
//                if($this->pLimit == $val)
//                    echo "<option selected=\"selected\" value='$val'>$val</option>";
//                else
//                    echo "<option value='$val'>$val</option>";
//            }
//            echo "</select>";

	if ($this->pEspc == "estadual")
	    echo "<select  style='display:none' id=\"selectEstados\">";
	else
	    echo "Estado: <select id=\"selectEstados\">";
	if ($this->pEstado == 0)
	    echo "<option selected=\"selected\" value='0'>Todos</option>";
	else
	    echo "<option value='0'>Todos</option>";
	foreach ($this->estados as $key => $val) {
	    if ($this->pEstado == $val["id"]) {
		echo "<option selected=\"selected\" value='{$val["id"]}'>{$val["nome"]}</option>";
		$this->nomeEstado = $val["nome"];
	    }
	    else
		echo "<option value='{$val["id"]}'>{$val["nome"]}</option>";
	}
	echo "</select>";
    }

    public function drawButtons() {
	$aDis = "rank_hover";
	$bDis = "rank_hover";
	$cDis = "rank_hover";
	$dDis = "rank_hover";

	$onclick_a = "onclick=\"sendData({$this->pOrdem_id},'{$this->pOrdem}',1,'{$this->pEspc}',1,{$this->pEstado})\"";
	$onclick_b = "onclick=\"sendData({$this->pOrdem_id},'{$this->pOrdem}'," . ($this->pPag - 1) . ",'{$this->pEspc}',{$this->pStart},{$this->pEstado})\"";
	$onclick_c = "onclick=\"sendData({$this->pOrdem_id},'{$this->pOrdem}'," . ($this->pPag + 1) . ",'{$this->pEspc}',{$this->pStart},{$this->pEstado})\"";
	$onclick_d = "onclick=\"sendData({$this->pOrdem_id},'{$this->pOrdem}',{$this->Paginas},'{$this->pEspc}',{$this->pStart},{$this->pEstado})\"";
	if ($this->Paginas <= 1) {
	    $aDis = "disabled";
	    $bDis = "disabled";
	    $cDis = "disabled";
	    $dDis = "disabled";

	    $onclick_a = "";
	    $onclick_b = "";
	    $onclick_c = "";
	    $onclick_d = "";
	} elseif ($this->pPag == 1) {
	    $aDis = "disabled";
	    $bDis = "disabled";
	    $onclick_a = "";
	    $onclick_b = "";
	} elseif ($this->Paginas == $this->pPag) {
	    $cDis = "disabled";
	    $dDis = "disabled";
	    $onclick_c = "";
	    $onclick_d = "";
	}
	echo "<div class='divPaginacaoRank'><div class=\"pagination\">
                    <ul>
                      <li class='$aDis'><a $onclick_a > &#60;&#60; </a></li>
                      <li class='$bDis'><a $onclick_b > &#60; </a></li>
                      <li class='disabled'><a> $this->pPag/{$this->Paginas} </a></li>
                      <li class='$cDis'><a $onclick_c > &#62; </a></li>
                      <li class='$dDis'><a $onclick_d > &#62;&#62; </a></li>
                     </ul>
                   </div></div>";


//            <input class='btn $aDis' type='button' value='<<' $onclick_a />
//            <input class='btn $bDis' type='button' value='<' $onclick_b />
//            <input class='btn disabled' type='button' value='$this->pPag/{$this->Paginas}' />
//            <input class='btn $cDis' type='button' value='>' $onclick_c />
//            <input class='btn $dDis' type='button' value='>>' $onclick_d />
//            </div>
    }

    public function drawLegenda() {
	$label1 = "bolinha ruim";
	$label2 = "bolinha média";
	$label3 = "bolinha boa";
	$label4 = "bolinha ótima";
	?>
            <script>$(document).ready(function(){
                $('.rank_arrow').tooltip();
            })</script>
	<div class='legendaRank'>
	    <div class='titleLegendaRank'>Faixas de desenvolvimento humano</div>
	    <table class="table table-bordered table-condensed td-custom">
		<tr>
		    <td><div class='bolinhaRank bolinhaOtimo'></div></td>
		    <td>Muito Alto</td>
		    <td>0,800 - 1,000</td>
		</tr>
		<tr>
		    <td><div class='bolinhaRank bolinhaBom'></div></td>
		    <td>Alto</td>
		    <td>0,700 - 0,799</td>
		</tr>
		<tr>
		    <td><div class='bolinhaRank bolinhaMedia'></div></td>
		    <td>Médio</td>
		    <td>0,600 - 0,699</td>
		</tr>
		<tr>
		    <td><div class='bolinhaRank bolinhaRuim'></div></td>
		    <td>Baixo</td>
		    <td>0,500 - 0,599</td>
		</tr>
		<tr>
		    <td><div class='bolinhaRank bolinhaMuitoRuim'></div></td>
		    <td>Muito Baixo</td>
		    <td>0,000 - 0,499</td>
		</tr>
	    </table>
	</div>
	<?
//            echo "<div class='fl_rank'><div></div><div style='text-align:center; width:62px'><small>0,801<br />-<br />1</small></div></div>";
//            echo "<div class='fl_rank'><div class='bolinhaRank bolinhaBom' data-original-title='Entre 0,651 e 0,800' title data-placement='bottom'></div><div>Alto</div><div style='text-align:center; width:40px'><small>0,651<br />-<br />0,800</small></div></div>";
//            echo "<div class='fl_rank'><div class='bolinhaRank bolinhaMedia' data-original-title='Entre 0,501 e 0,650' title data-placement='bottom'></div><div>Médio</div><div style='text-align:center; width:40px'><small>0,501<br />-<br />0,650</small></div></div>";
//            echo "<div class='fl_rank'><div class='bolinhaRank bolinhaRuim' data-original-title='IDH menor que 0,500' title data-placement='bottom'></div><div>Baixo</div><div style='text-align:center; width:40px'><small>0<br />-<br />0,500</small></div></div>";
    }

    public function writeButton() {
	?>
	<button class="gray_button big_bt" id="imgTab6" data-original-title='Download da lista em formato csv (compatível com o Microsoft Excel e outras planilhas eletrônicas).' title data-placement='bottom' icon="download_2" onclick="sendDataDownload()">
	    <img src="img/icons/download_2.png"/>
	</button>
	<?
    }
    
    public function drawAnoSelect(){
        
        ?>
        
          <div>
                    <div class='labels'>
                        <span class="one">1991</span>
                        <span class="two">2000</span>
                        <span class="tree">2010</span>
                    </div>
                </div>
                <div class="sliderDivFather">
                    <div class="sliderDivIn">
                        <input type='text' id="ranking_year_slider" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" />
                    </div>    
                </div>  
        <?
        
        
        return;
        echo "<select id='selct_ano' name='selct_ano'>";
            if($this->fkAno == 1) echo "<option value='1' selected='selected'>1991</option>"; else echo "<option value='1'>1991</option>";
            if($this->fkAno == 2) echo "<option value='2' selected='selected'>2000</option>"; else echo "<option value='2'>2000</option>";
            if($this->fkAno == 3) echo "<option value='3' selected='selected'>2010</option>"; else echo "<option value='3'>2010</option>";
        echo "</select>";
    }

}


function convertAnoIDtoLabel($anoLabel){
    switch((int)$anoLabel){
        case 1:
            return 1991;
        case 2:
            return 2000;
        case 3:
            return 2010;
    }
}
?>
