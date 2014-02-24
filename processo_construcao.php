<?php 
    ob_start(); 
    
    require_once "web/processo_construcao_base.php";
    
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
