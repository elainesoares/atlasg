<?php 
    ob_start(); 
    
    require_once "web/download.php";
    $title = "Download";
    $meta_title = 'Download dos dados do Atlas do Desenvolvimento Humano no Brasil 2013';
    $meta_description = 'Faça download de todos  os indicadores e espacialidades disponíveis e/ou selecione as dados desejados.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
