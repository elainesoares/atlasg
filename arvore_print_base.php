<?php 
    ob_start(); 
    
    require_once "web/arvore_print.php";
    
    $title = 'Impressão Árvore do IDHM';
    $title_print = 'Árvore do IDHM ';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>