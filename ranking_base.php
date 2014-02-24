<?php 
    ob_start(); 
    
    require_once "web/ranking.php";
    $title = $lang_mng->getString("rankin_title");
    $meta_title = $lang_mng->getString("rankin_metaTitle");
    $meta_description = $lang_mng->getString("rankin_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>

