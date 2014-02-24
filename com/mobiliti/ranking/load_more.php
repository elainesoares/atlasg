<?php
    require_once "../../../config/config_path.php";
    require_once "../../../config/config_gerais.php";
    require_once '../../../config/conexao.class.php';
    require_once MOBILITI_PACKAGE."/consulta/bd.class.php";
    require_once MOBILITI_PACKAGE."/ranking/ranking.class.php";
    
    extract($_POST);
    $ranking = new Raking($ordem_id,$ordem,$pag,$espc,$start,$estado,$estados_pos,true);
    $ranking->draw();
?>
