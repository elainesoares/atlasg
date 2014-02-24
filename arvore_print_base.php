<?php 
    ob_start(); 
    
    require_once "web/arvore_print.php";
    
    $title = $lang_mng->getString("arvore_titleprint");
    $title_print = $lang_mng->getString("arvore_tituloprint");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>