<?php 
    ob_start(); 
    
//    session_start();
    require_once "web/arvore.php";    
    $title = $lang_mng->getString("arvore_title");
    $meta_title = $lang_mng->getString("arvore_metaTitle");
    $meta_description = $lang_mng->getString("arvore_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
