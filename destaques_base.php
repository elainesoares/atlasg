<?php 
    ob_start(); 
    
    require_once "web/destaques.php";
    $title = "Destaques";
    $meta_title = '';
    $meta_description = 'O Atlas do Desenvolvimento Humano no Brasil 2013 é uma plataforma de consulta ao Índice de Desenvolvimento Humano Municipal – IDHM - de 5.565 municípios brasileiros, além de mais de 180 indicadores de população, educação, habitação, saúde, trabalho, renda e vulnerabilidade, com dados extraídos dos Censos Demográficos de 1991, 2000 e 2010.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
