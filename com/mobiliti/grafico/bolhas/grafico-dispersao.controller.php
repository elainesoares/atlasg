<?php
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: {$path_dir}404");
    }

    require_once "../../../../config/config_path.php";
    require_once '../../../../config/conexao.class.php';
    require_once MOBILITI_PACKAGE.'util/protect_sql_injection.php';
    require_once "../../consulta/bd.class.php";
    require_once "../../consulta/Consulta.class.php";
    require_once "GraficoDispersao.class.php";
    
//    $json_lugares = $_POST['json_lugares'];
//    $json_indicadores = $_POST['json_indicadores'];
//    
//    $grafico = new GraficoDispersao($json_lugares, $json_indicadores);
//    $grafico->draw();
    
    #instanciando o objeto
    $ocon = new Conexao();

    // É necessário colocar o import da função antes de utilizá-la
    include_once("../../util/protect_sql_injection.php");

    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    $espacialidade = (int)$_POST["e"];
//    echo $espacialidade.'<br />';

    $lugares = array();
    $indicadores = array();
    if(isset($_POST["l"]))
    {
       $lugares = explode(",", $_POST["l"]);
    }
//    echo $lugares[0].'<br /> ';
    
    if(isset($_POST["i"]))
    {
       $indicadores = explode(",", $_POST["i"]);
    }
//    echo $indicadores[0].' '.$indicadores[1].' '.$indicadores[2].' '.$indicadores[3].'<br /> ';
    $ano = (int)$_POST["a"];
//    echo $ano.'<br />';

    /* =================== LIMPA REQUISIÇÃO ======================================== */
    $_GET = null;
    $_POST = null;
    $_REQUEST = null;
    
    $grafico = new GraficoDispersao($lugares, $indicadores, $espacialidade, $ano);
    $grafico->draw();
    
    
?>
