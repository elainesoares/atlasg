<?php
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: {$path_dir}404");
    }
    require_once '../../../config/config_path.php';
    require_once '../../../config/config_gerais.php';
    require_once '../util/protect_sql_injection.php';
    ini_set( "display_errors", 0);
    ob_start("ob_gzhandler");
    
    function retira_acentos($texto){ 
        $array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
        , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
        $array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
        , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
        return str_replace( $array1, $array2, $texto); 
    } 
    
    $consulta = $_POST["consulta"];
    
    //==========================================================================
    //Tramento anti sql injection
    //==========================================================================
    
    $stringTratada = "";
    $stringTratada = retira_acentos(cidade_anti_sql_injection($consulta));
    
    //==========================================================================
    //Consulta
    //==========================================================================
    
    $sql_lugares = "";
    $sql_indicadores = "";
    if(file_exists("../preconsultas/$stringTratada.json"))
        include "../preconsultas/$stringTratada.json";
?>
