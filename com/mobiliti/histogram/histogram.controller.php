<?php
    include("./histogram.class.php");
    include("./HistogramResponse.class.php");
    include_once("../../../config/conexao.class.php");
    include_once("../../../config/config_path.php");
    include_once("../../../config/config_gerais.php");
    include_once("../consulta/Consulta.class.php");
    include_once("../util/PublicMethods.class.php");
    
    $ocon = new Conexao();
    
    $consulta_url = array_reverse(explode("/", $_POST['query_object']));
    
    /* obtendo espacialidade */
    $espc =  array_pop($consulta_url);
    //$espc =  array_pop($consulta_url);

    switch ($espc){
        case "municipal":
            $espacialidade = Consulta::$ESP_MUNICIPAL;
            break;
        case "regional":
            $espacialidade = Consulta::$ESP_REGIONAL;
            break;
        case "estadual":
            $espacialidade = Consulta::$ESP_ESTADUAL;
            break;
        case "udh":
            $espacialidade = new Consulta::$ESP_UDH;
            break;
        case "rm":
            $espacialidade = new Consulta::$ESP_REGIAOMETROPOLITANA;
            break;
        case "ri":
            $espacialidade = new Consulta::$ESP_REGIAODEINTERESSE;
            break;
        case "mesorregional":
            $espacialidade = new Consulta::$ESP_MESOREGIAO;
            break;
        case "pais":
            $espacialidade = new Consulta::$ESP_PAIS;
            break;
        case "mesorregional":
            $espacialidade = new Consulta::$ESP_MICROREGIAO;
            break;
    }

    /* obtendo filtros e indicadores*/
    $lfiltros = array();
    $consulta_url = array_reverse($consulta_url);
    $i = 0;
    
    while ($i < sizeof($consulta_url)){
        if($consulta_url[$i] == "filtro"){
            $lfiltro = array("tipo" => $consulta_url[$i+1], "valores" => $consulta_url[$i+2]);
            array_push($lfiltros, $lfiltro);
        }
        if($consulta_url[$i] == "indicador"){
            $lindicador = explode("-",$consulta_url[$i+1]);
        }
        $i++;
    }

    $filtros = array();
    
    /* convertendo filtros em valores*/
    foreach ($lfiltros as $value) {
        switch ($value["tipo"]){
            case "udh":
                break;
            case "municipio":
                $valores = PublicMethods::getCityID( explode(",",$value["valores"]), $ocon);  
                $flt = array("tipo" => Filtro::$FILTRO_MUNICIPIO, "valores" => $valores);
                break;
            case "microrregiao":
                //$consulta = new Consulta(Consulta::$ESP_REGIONAL);
                break;
            case "mesorregiao":
                //$consulta = new Consulta(Consulta::$ESP_REGIONAL);
                break;
            case "rm":
                //$consulta = new Consulta(Consulta::$ESP_REGIONAL);
                break;
            case "estado":
                $valores = PublicMethods::getStatesID( explode(",",$value["valores"])  , $ocon);
                $flt = array("tipo" => Filtro::$FILTRO_ESTADO, "valores" => $valores);
                break;
            case "regiao":
                $valores = PublicMethods::getRegionID( explode(",",$value["valores"])  , $ocon);
                $flt = array("tipo" => Filtro::$FILTRO_REGIAO, "valores" => $valores);
                break;
        }
        array_push($filtros, $flt);
    }
    
    /* obtendo indicador */
    $ano = 2010;
    if(sizeof($lindicador) > 1){
        $ano = $lindicador[1];
    }

    $indicador =  PublicMethods::getIndicator($lindicador[0], $ano, $ocon);
    
    $h = new Histogram($indicador['indc'], $indicador['ano'], $espacialidade, $ocon);
    
    //explodindo os filtros
    foreach ($filtros as $filtro) {
        if($filtro['tipo'] == Filtro::$FILTRO_REGIAO){
            $h->selectByRegions($filtro['valores'],$indicador['indc'],$indicador['ano']);
        }
        else if($filtro['tipo'] == Filtro::$FILTRO_ESTADO){
            $h->selectByStates($filtro['valores'],$indicador['indc'],$indicador['ano']);
        }   
        
        else if($filtro['tipo'] == Filtro::$FILTRO_MUNICIPIO){
            $h->selectByCities($filtro['valores'],$indicador['indc'],$indicador['ano']);
        }
    }
        
    $h->getFunctions($ocon);
    
    $response = new HistogramResponse($h->DrawHistograma());

    echo $response->getJSON();
	
?>