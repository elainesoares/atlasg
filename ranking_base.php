<?php 
    ob_start(); 
    
    require_once "web/ranking.php";
    $title = 'Ranking';
    $meta_title = 'Ranking do IDM dos Municípios e Estados do Brasil';
    $meta_description = 'Visualize a posição das cidades e estados brasileiros no ranking do Índice de Desenvolvimento Humano Municipal.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>

