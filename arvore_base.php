<?php 
    ob_start(); 
    
    require_once "web/arvore.php";    
    $title = 'Árvore do IDHM';
    $meta_title = 'Árvore do Índice de Desenvolvimento Humano no Brasil 2013';
    $meta_description = 'Visualize os Indicadores Socieconômicos do Brasil no formato da árvore de IDHM';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
