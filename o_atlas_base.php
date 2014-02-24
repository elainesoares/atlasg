<?php 
    ob_start(); 
    
    require_once "web/o_atlas.php";
    $title = $lang_mng->getString("atlas_title");
    $meta_title = $lang_mng->getString("atlas_metaTitle");
    $meta_description = $lang_mng->getString("atlas_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
