<?php

require_once MOBILITI_PACKAGE . 'display/controller/chart/FunctionsChart.class.php';

function getChartDesenvolvimentoHumano($idMunicipio) {

    $chart = new FunctionsChart();

    $renda = json_encode($chart->getRenda($idMunicipio));
    $educacao = json_encode($chart->getEducacao($idMunicipio));
    $longevidade = json_encode($chart->getLongevidade($idMunicipio));
    $idhm = $chart->getIDHMunicipalEvolucao($idMunicipio);

    echo '<script language="javascript" type="text/javascript">graficoDesenvolvimentoHumanoIDHM();</script>';

    return "<div style='width: 100%; height: 300px;'>
                <div id='chartDesenvolvimentoHumanoIDHM' style='float:left; width: 700px; height: 300px;'>            
                    <input id='renda' type='hidden' value=' " . $renda . "' /> 
                    <input id='educacao' type='hidden' value='" . $educacao . "' />
                    <input id='longevidade' type='hidden' value='" . $longevidade . "' /> 
                    
                </div>
                <div id='idhm' style='float: left; width: 100px; height: 245px;' >
                        <table border='0' style='height: 245px; font-size: 19pt;' >
                        <tr>
                          <td style='height: 50px; text-align:center; font-size:12pt;'>IDHM</td>  
                        </tr>
                        <tr>
                          <td style='height: 50px;'><b>". number_format($idhm[0]["valor"], 3,',','.') ."</b></td>  
                        </tr>
                        <tr>
                          <td style='height: 50px;'><b>". number_format($idhm[1]["valor"], 3,',','.') ."</b></td>
                        </tr>
                        <tr>
                          <td style='height: 50px;'><b>". number_format($idhm[2]["valor"], 3,',','.') ."</b></td>
                        </tr>
                        </table>
                </div>
            </div>";
         
}

function getChartEvolucao($idMunicipio) {

    $chart = new FunctionsChart();

    $idhm_max_min_ano = json_encode($chart->getIDHMaiorMenorAno());
    $idhm_mun = json_encode($chart->getIDHMunicipalEvolucao($idMunicipio));
    $idhm_media_brasil = json_encode($chart->getIDHMediaBrasil());
    $idhm_estado = json_encode($chart->getIDHEstado($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoEvolucaoIDHM();</script>';

    return "<div id='chartEvolucao' style='width: 100%; height: 510px;'>            
            <input id='idhm_mun' type='hidden' value=' " . $idhm_mun . "' /> 
            <input id='idhm_max_min_ano' type='hidden' value='" . $idhm_max_min_ano . "' />
            <input id='idhm_media_brasil' type='hidden' value='" . $idhm_media_brasil . "' /> 
            <input id='idhm_estado' type='hidden' value='" . $idhm_estado . "' />  
         </div>";
}

function getChartTaxaCrescimento($idMunicipio) {

    $chart = new FunctionsChart();

    $idhm_max_min_ano = json_encode($chart->getIDHMaiorMenorAno());
    $idhm_mun = json_encode($chart->getIDHMunicipalEvolucao($idMunicipio));
    $idhm_media_brasil = json_encode($chart->getIDHMediaBrasil());
    $idhm_estado = json_encode($chart->getIDHEstado($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoTaxaCrescimentoIDHM();</script>';

    return "<div id='taxaCrescimentoIDHM' style='width: 100%; height: 600px;'>            
            <input id='idhm_mun' type='hidden' value=' " . $idhm_mun . "' /> 
            <input id='idhm_max_min_ano' type='hidden' value='" . $idhm_max_min_ano . "' />
            <input id='idhm_media_brasil' type='hidden' value='" . $idhm_media_brasil . "' /> 
            <input id='idhm_estado' type='hidden' value='" . $idhm_estado . "' />  
         </div>";
}

function getChartFluxoEscolar($idMunicipio) {

    $chart = new FunctionsChart();
	
    $freq_esc_5a6 = json_encode($chart->getFreqEscolar5a6($idMunicipio));
    $freq_esc_11a13 = json_encode($chart->getFreqEscolar11a13($idMunicipio));
    $freq_esc_15a17 = json_encode($chart->getFreqEscolar15a17($idMunicipio));
    $freq_esc_18a20 = json_encode($chart->getFreqEscolar18a20($idMunicipio));
	
    echo '<script language="javascript" type="text/javascript">graficoFluxoEscolar();</script>';

    return "<div id='chartFluxoEscolar' style='width: 100%; height: 600px;'>            
            <input id='freq_esc_5a6' type='hidden' value=' " . $freq_esc_5a6 . "' /> 
            <input id='freq_esc_11a13' type='hidden' value='" . $freq_esc_11a13 . "' />
            <input id='freq_esc_15a17' type='hidden' value='" . $freq_esc_15a17 . "' /> 
            <input id='freq_esc_18a20' type='hidden' value='" . $freq_esc_18a20 . "' />  
         </div>";
}

function getChartFrequenciaEscolar($idMunicipio) {

    $chart = new FunctionsChart();

    $freq_esc_mun = json_encode($chart->getFreqEscolarFaixaEtariaMun($idMunicipio));
    $freq_esc_uf = json_encode($chart->getFreqEscolarFaixaEtariaEstado($idMunicipio));
    $freq_esc_pais = json_encode($chart->getFreqEscolarFaixaEtariaBrasil($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFrequenciaEscolar();</script>';

    return "<div id='chartFrequenciaEscolar' style='width: 100%; height: 600px;'>            
            <input id='freq_esc_mun' type='hidden' value=' " . $freq_esc_mun . "' /> 
            <input id='freq_esc_uf' type='hidden' value='" . $freq_esc_uf . "' />
            <input id='freq_esc_pais' type='hidden' value='" . $freq_esc_pais . "' /> 
         </div>";
}

function getChartEscolaridadePopulacao($idMunicipio) {

    $chart = new FunctionsChart();

    $freq_1991 = json_encode($chart->getFrequenciaEscolar25ouMais1991($idMunicipio));
    $freq_2000 = json_encode($chart->getFrequenciaEscolar25ouMais2000($idMunicipio));
    $freq_2010 = json_encode($chart->getFrequenciaEscolar25ouMais2010($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoEscolaridadePop91();</script>';
    echo '<script language="javascript" type="text/javascript">graficoEscolaridadePop00();</script>';
    echo '<script language="javascript" type="text/javascript">graficoEscolaridadePop10();</script>';


    return "<div id='chart_escola' style='width:100%; height: 330px'>
				<div><img src='./img/25_ou_mais.png' width='736' height='47' style='margin-left:95px; z-index:1; position:absolute;'/></div>
                <div id='chartEscolaridadePop91' style='width: 360px; height: 283px; float:left; position:none;'>            
                    <input id='freq_1991' type='hidden' value=' " . $freq_1991 . "' /> 
                </div>
                <div id='chartEscolaridadePop00' style='width: 285px; height: 283px;float:left; position:none;'>            
                    <input id='freq_2000' type='hidden' value=' " . $freq_2000 . "' /> 
                </div>
                <div id='chartEscolaridadePop10' style='width: 230px; height: 283px;float:left; position:none;'>            
                    <input id='freq_2010' type='hidden' value=' " . $freq_2010 . "' /> 
                </div>
           </div>";
}

function getChartFrequenciaDe6a14($idMunicipio) {

    $chart = new FunctionsChart();

    $freq_esc_6a14 = json_encode($chart->getFrequenciaEscolar6a14Anos($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFrequenciaEscolarDe6a14Anos();</script>';
	
    return "<div id='chart_freq' style='width: 100%; height: 400px; '> 
                <div id='chart_freq_6a14' style='width: 100%; height: 360px; float:left;' >
                    <input id='freq_esc_6a14' type='hidden' value=' " . $freq_esc_6a14 . "' /> 
                </div>
         </div>";
}

function getChartFrequenciaDe15a17($idMunicipio) {

    $chart = new FunctionsChart();
    
    $freq_esc_15a17 = json_encode($chart->getFrequenciaEscolar15a17Anos($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFrequenciaEscolarDe15a17Anos();</script>';

    return "<div id='chart_freq' style='width: 100%; height: 400px; '> 
                <div id='chart_freq_15a17' style='width: 100%; height: 360px; float:left;' >
                    <input id='freq_esc_15a17' type='hidden' value='" . $freq_esc_15a17 . "' />
                </div>
         </div>";
}

function getChartFrequenciaDe18a24($idMunicipio) {

    $chart = new FunctionsChart();

    $freq_esc_18a24 = json_encode($chart->getFrequenciaEscolar18a24Anos($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFrequenciaEscolarDe18a24Anos();</script>';

    return "<div id='chart_freq' style='width: 100%; height: 420px;'> 
                <div id='chart_freq_18a24' style='width: 100%; height: 360px; float:left;' >
                    <input id='freq_esc_18a24' type='hidden' value='" . $freq_esc_18a24 . "' />
                </div>
         </div>";
}

function getChartTrabalho($idMunicipio) {

    $chart = new FunctionsChart();

    $trabalho = json_encode($chart->getTrabalho2010($idMunicipio));
    $trabalho_ativo = json_encode($chart->getTrabalhoAtivos2010($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoTrabalho();</script>';
    echo '<script language="javascript" type="text/javascript">graficoTrabalhoAtivos();</script>';

    return "<div style='height: 50px; text-align:center; font-size: 15pt; font-weight: bold;'>Taxa de Atividade e de Desocupação 18 anos ou mais - 2010</div>
		<div><img src='./img/pop_econ_ativa.png' width='348' height='57' style='margin-left:80px'/></div>	
         <div id='chart_trab' style='width: 100%; height: 457px; margin-left:8%;'> 
               <div id='chart_trabalho' style='width: 465px; height: 465px; float:left;' >
                    <input id='trabalho_perfil' type='hidden' value=' " . $trabalho . "' /> 
                </div>
                <div id='chart_trabalho_ativos' style='width: 350px; height: 400px; float:left; margin-top: 45px;' >
                    <input id='trabalho_ativo' type='hidden' value=' " . $trabalho_ativo . "' /> 
                </div>
         </div>";
}

function getChartPiramideEtaria1($idMunicipio) {

    $chart = new FunctionsChart();

	$piram_fem_1991_arr = $chart->getPiramideEtariaFemin1991($idMunicipio);
    $piram_fem_1991 = json_encode($piram_fem_1991_arr);
    $piram_masc_1991 = json_encode($chart->getPiramideEtariaMasc1991($idMunicipio));
    $piram_total_1991 =  json_encode($chart->getPiramideEtariaTotal1991($idMunicipio));
	
    echo '<script language="javascript" type="text/javascript">graficoFaixaEtaria1991();</script>';
    
    return "<div id='chart_piramide' style='width: 100%; '>
                <div id='titulo' style='height: 400px; margin-top: 30px;' >
                    <div  style='width: 450px; margin: 0 auto;'>
                        <div style='float: left; height: 40px; width: 75px; font-weight:bold; font-size: 20pt; margin-top: 11px; '>1991</div>
                            <div  style=' color: #FF66CC; font-size: 13pt; '>
                                    <b>Pirâmide etária - ". $piram_fem_1991_arr[0]["nome"] . " - ". $piram_fem_1991_arr[0]["uf"] . "</b>
                                    <div style=' color: #FF66CC; font-size: 11pt; '>Distribuição por  Sexo, segundo os grupos de idade</div>
                            </div>
                    </div>
                    <div id='chart_piram_1991' style='width: 800px;  float:left; ' >
                        <input id='piram_fem_1991' type='hidden' value=' " . $piram_fem_1991 . "' /> 
                        <input id='piram_masc_1991' type='hidden' value='" . $piram_masc_1991 . "' />
                        <input id='piram_total_1991' type='hidden' value='" . $piram_total_1991 . "' />
                    </div>      
               </div>
         </div>";
}

function getChartPiramideEtaria2($idMunicipio) {

    $chart = new FunctionsChart();
	$piram_fem_2000_arr= $chart->getPiramideEtariaFemin2000($idMunicipio);
    $piram_fem_2000 = json_encode($piram_fem_2000_arr);

    $piram_masc_2000 = json_encode($chart->getPiramideEtariaMasc2000($idMunicipio));
    $piram_total_2000 =  json_encode($chart->getPiramideEtariaTotal2000($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFaixaEtaria2000();</script>';

    return "<div id='chart_piramide' style='width: 100%; '>
                <div id='titulo' style='height: 400px; margin-top: 30px;' >
                    <div  style='width: 450px; margin: 0 auto;'>
                        <div style='float: left; height: 40px; width: 75px; font-weight:bold; font-size: 20pt; margin-top: 11px; '>2000</div>
                            <div  style=' color: #FF66CC; font-size: 13pt; '>
                                    <b>Pirâmide etária - ". $piram_fem_2000_arr[0]["nome"] . " - ". $piram_fem_2000_arr[0]["uf"] . "</b>
                                    <div style=' color: #FF66CC; font-size: 11pt; '>Distribuição por  Sexo, segundo os grupos de idade</div>
                        </div>
                    </div>
                    <div id='chart_piram_2000' style='width: 800px; float:left; ' >
                        <input id='piram_fem_2000' type='hidden' value=' " . $piram_fem_2000 . "' /> 
                        <input id='piram_masc_2000' type='hidden' value='" . $piram_masc_2000 . "' />
                        <input id='piram_total_2000' type='hidden' value='" . $piram_total_2000 . "' />
                    </div>  
		</div>
         </div>";
}

function getChartPiramideEtaria3($idMunicipio) {

    $chart = new FunctionsChart();

	$piram_fem_2010_arr= $chart->getPiramideEtariaFemin2010($idMunicipio);
    $piram_fem_2010 = json_encode($piram_fem_2010_arr);

    $piram_masc_2010 = json_encode($chart->getPiramideEtariaMasc2010($idMunicipio));
    $piram_total_2010 =  json_encode($chart->getPiramideEtariaTotal2010($idMunicipio));

    echo '<script language="javascript" type="text/javascript">graficoFaixaEtaria2010();</script>';

    return "<div id='chart_piramide' style='width: 100%; '>
                <div id='titulo' style='height: 400px; margin-top: 30px;' >
                    <div  style='width: 450px; margin: 0 auto;'>
                        <div style='float: left; height: 40px; width: 75px; font-weight:bold; font-size: 20pt; margin-top: 11px; '>2010</div>
                            <div  style=' color: #FF66CC; font-size: 13pt; '>
                                    <b>Pirâmide etária - ". $piram_fem_2010_arr[0]["nome"] . " - ". $piram_fem_2010_arr[0]["uf"] . "</b>
                                    <div style=' color: #FF66CC; font-size: 11pt; '>Distribuição por  Sexo, segundo os grupos de idade</div>
                        </div>
                    </div>
                    <div id='chart_piram_2010' style='width: 800px; float:left;' >
                        <input id='piram_fem_2010' type='hidden' value=' " . $piram_fem_2010 . "' /> 
                        <input id='piram_masc_2010' type='hidden' value='" . $piram_masc_2010 . "' />
                        <input id='piram_total_2010' type='hidden' value='" . $piram_total_2010 . "' />    
                    </div>
		</div>
         </div>";
}

?>
  