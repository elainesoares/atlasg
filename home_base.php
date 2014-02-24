<?php 
    ob_start(); 
    
    require_once "web/home.php";
    $title = $lang_mng->getString("home_title");
    $meta_title = $lang_mng->getString("home_metaTitle");
    $meta_description = $lang_mng->getString("home_metaDescricao");
    $content = ob_get_contents();
    ob_end_clean();
            
//    $get = explode("/",$_SERVER["REQUEST_URI"]);  
//    if ($base !== $get[1])
//        header("location:home/teste");
//    
//    $dominioUrl = "http://" . $_SERVER['SERVER_NAME'];
//    if ($dominio != $dominioUrl)
//        header("location:home/teste");
    
    include "web/base.php";
?>
