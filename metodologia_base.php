<?php 
    ob_start(); 
    
    require_once "web/metodologia_.php";
    $title = 'Metodologia';
    $meta_title = 'Sobre o Atlas';
    $meta_description = 'O conceito de desenvolvimento humano nasceu definido como um processo de ampliação das escolhas das pessoas para que elas tenham capacidades e oportunidades para serem aquilo que desejam ser.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?>
