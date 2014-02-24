<?php 
    ob_start(); 
    
    require_once "web/download.php";
    $title = $lang_mng->getString("download_title");
    $meta_title = $lang_mng->getString("download_metaTitle");
    $meta_description = $lang_mng->getString("download_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
