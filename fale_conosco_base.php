<?php 
    ob_start(); 
    
    require_once "web/fale_conosco.php";
    
    $title = "Fale Conosco";
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>