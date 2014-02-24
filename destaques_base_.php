<?php 
    ob_start(); 
    
    require_once "web/destaques.php";
    $title = $lang_mng->getString("destaques_titleAba");
    $meta_title = $lang_mng->getString("destaques_Title");
    $meta_description = $lang_mng->getString("destaques_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
