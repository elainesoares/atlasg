<?php
    
    require_once '../../../config/config_path.php';
    require_once '../../../config/conexao.class.php';
    require_once '../consulta/bd.class.php';
    require_once '../util/protect_sql_injection.php';
    //ob_start("ob_gzhandler");
    
    //==========================================================================
    //Constates
    //==========================================================================
    
    define("TABELA_MUNICIPIO", 2);
    define("TABELA_REGIAO", 3);
    define("TABELA_ESTADO", 4);
    define("TABELA_UDH", 5);
    define("TABELA_REGIAOMETROPOLITANA", 6);
    define("TABELA_REGIAODEINTERESSE", 7);
    define("TABELA_MESORREGIAO", 8);
    define("TABELA_MICRORREGIAO", 9);
    define("TABELA_PAIS", 10);
    define("TABELA_MUNICIPIO_ESTADO", 1);
    
    //==========================================================================
    //Local functions
    //==========================================================================
    
    function replaceTags($startPoint, $endPoint, $newText, $source){
        $startTagPos = strrpos($source, $startPoint);
        $endTagPos = strrpos($source, $endPoint);
        $tagLength = $endTagPos - $startTagPos + 1;
        if($tagLength < 3)
            return $source;
        return substr_replace($source, $newText, $startTagPos, $tagLength);
    }
    function retira_acentos($texto){ 
        $array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
        , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
        $array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
        , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
        return str_replace( $array1, $array2, $texto); 
    } 
    
    //==========================================================================
    //Load variables
    //==========================================================================
    
    $db = new bd();
    $search = $_POST['s'];
    $tabela = $_POST['_in'];;
    
    //==========================================================================
    //Unset globals variables
    //==========================================================================
    
    unset($_POST);
    
    //==========================================================================
    //Tramento anti sql injection
    //==========================================================================
    
    $stringTratada = "";
    $search = replaceTags(" (", ")", "", $search);
    $stringTratada = retira_acentos(cidade_anti_sql_injection($search));
    //==========================================================================
    //Consulta
    //==========================================================================
    
    
    switch ($tabela) {
        case TABELA_MUNICIPIO:
            $SQL1 = "SELECT municipio.nome,estado.uf,municipio.id FROM municipio
                 INNER JOIN estado ON (estado.id = municipio.fk_estado)
                 WHERE sem_acento(municipio.nome) ILIKE '$stringTratada%' ORDER BY municipio.nome LIMIT 9";
            break;
        case TABELA_ESTADO:
            $SQL1 = "SELECT nome,estado.uf,id FROM estado
                 WHERE sem_acento(nome) ILIKE '$stringTratada%' ORDER BY nome LIMIT 9";
            break;
        case TABELA_REGIAO:
            $SQL1 = "SELECT nome,id FROM regiao
                 WHERE sem_acento(nome) ILIKE '$stringTratada%' ORDER BY nome LIMIT 9";
            break;
        case TABELA_UDH:
            $SQL1 = "SELECT nome,id FROM udh
                 WHERE sem_acento(nome) ILIKE '$stringTratada%' ORDER BY nome LIMIT 9";
            break;
        case TABELA_MUNICIPIO_ESTADO:
            $SQL1 = "SELECT municipio.nome,estado.uf,municipio.id FROM municipio
                 INNER JOIN estado ON (estado.id = municipio.fk_estado)
                 WHERE sem_acento(municipio.nome) ILIKE '$stringTratada%' ORDER BY municipio.nome LIMIT 9";
//             $SQL2 = "SELECT nome,estado.uf,id FROM estado
//                 WHERE sem_acento(nome) ILIKE '$stringTratada%' ORDER BY nome LIMIT 10";
             break;
    }
    $Resultante = array();
    $ArrayId = array();
    $result = $db->ExecutarSQL($SQL1);
    foreach($result as $key){
        $ArrayId[] = $key['id'];
        $Resultante[] = array(
                        'nome'=>$key['nome'],
                        'uf'=>$key['uf'],
                        'id'=>$key['id']
                 );
    }
    $c = 10 - count($result);
    if($c > 0){
        if(count($ArrayId) > 0){
            $conjunto = implode(',', $ArrayId);
        }
        else
            $conjunto = 0;
        
        switch ($tabela) {
            case TABELA_MUNICIPIO:
                $SQL2 =  "SELECT municipio.nome,estado.uf,municipio.id FROM municipio
                         INNER JOIN estado ON (estado.id = municipio.fk_estado)
                         WHERE sem_acento(municipio.nome) ILIKE '%$stringTratada%' and municipio.id not in ($conjunto) ORDER BY municipio.nome LIMIT $c";
                break;
            case TABELA_ESTADO:
                $SQL2 = "SELECT nome, estado.uf, id FROM estado
                     WHERE sem_acento(nome) ILIKE '%$stringTratada%' and id not in ($conjunto) ORDER BY nome LIMIT $c";
                break;
            case TABELA_REGIAO:
                $SQL2 = "SELECT nome,id FROM regiao
                     WHERE sem_acento(nome) ILIKE '%$stringTratada%' and id not in ($conjunto)  ORDER BY nome LIMIT $c";
                break;
            case TABELA_UDH:
                $SQL2 = "SELECT nome,id FROM udh
                     WHERE sem_acento(nome) ILIKE '%$stringTratada%' and id not in ($conjunto)  ORDER BY nome LIMIT $c";
                break;
            case TABELA_MUNICIPIO_ESTADO:
             $SQL2 = "SELECT nome,estado.uf,id FROM estado
                 WHERE sem_acento(nome) ILIKE '$stringTratada%' ORDER BY nome LIMIT $c";
             break;
        }
        
        $result = $db->ExecutarSQL($SQL2);
        foreach($result as $key){
            $Resultante[] = array(
                            'nome'=>$key['nome'],
                            'uf'=>$key['uf'],
                        'id'=>$key['id']
                     );
        }
    }
    
    if(count($Resultante) == 0){
        $Json[] = '003';
        die(json_encode($Json));
    }
    $Json = array();
    foreach($Resultante as $key){
        $Json[] =array(
                        'nome'=>$key['nome'],
                        'uf'=>$key['uf'],
                        'id'=>$key['id']
                 );
    }
    echo json_encode($Json);
    
?>
