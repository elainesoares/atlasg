<?php 
    ob_start(); 
    
    require_once "web/imprensa.php";
    
    $title = "Imprensa";
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>