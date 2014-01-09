<?php
    require_once "../../../../config/config_path.php";
    require_once '../../../../config/conexao.class.php';
    require_once MOBILITI_PACKAGE.'util/protect_sql_injection.php';
    require_once "../../consulta/bd.class.php";
    require_once "../../consulta/Consulta.class.php";
    require_once "GraficoLinhas.class.php";
    
    #instanciando o objeto
    $ocon = new Conexao();

    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    $espacialidade = (int)$_POST["e"];
//    echo $espacialidade.'<br />';

    $lugares = array();
    $indicadores = (int)$_POST['i'];
    
    if(isset($_POST["l"]))
    {
       $lugares = explode(",", $_POST["l"]);
    }
    
    $ano = (int)$_POST["a"];

    /* =================== LIMPA REQUISIÇÃO ======================================== */
    $_GET = null;
    $_POST = null;
    $_REQUEST = null;
    
    $grafico = new GraficoLinhas($lugares, $indicadores, $espacialidade, $ano);
    $grafico->draw();
?>
