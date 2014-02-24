<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
    header("Location: {$path_dir}404");
}

include_once("../../../../config/config_path.php");
include_once("../../../../config/config_gerais.php");
include_once("../../../../config/conexao.class.php");
include_once("../../map/Quantil.php");
include_once("../../consulta/Consulta.class.php");
include_once("../../util/Color.php");

$indc = $_POST["indicador"];
$ano  = $_POST["ano"];
$spc  = $_POST["espacialidade"];
$response = array();

$quantil = new Quantil();
$con = new Conexao();

$quantil->setLink($con->open());


$decimais_sql = "SELECT decimais FROM variavel WHERE id = $indc;";
$r_decimais = pg_query($con->open(), $decimais_sql) or die("Nao foi possivel executar a consulta!");
$decimais_obj = pg_fetch_object($r_decimais);


if($decimais_obj != null)
{
    $decimais = ($decimais_obj->decimais != null) ? (int)$decimais_obj->decimais : 0;
}
else
{
    $response["msg"] = "Erro ao obter quantidade de casas decimais.";
    $response["status"] = "no_operation";
    echo json_encode($response);
    die();
}


if($ano == "all")
{
    switch ($spc)
    {
        case Consulta::$ESP_ESTADUAL:
            
            
            $quartis_info1 = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_estado v WHERE v.fk_ano_referencia = 3 and v.fk_variavel = $indc;";              
            $quartis_data1 = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_estado v WHERE v.fk_ano_referencia = 3 and v.fk_variavel = $indc ORDER BY valor;";                          
            break;
        
        case Consulta::$ESP_MUNICIPAL:
            
            $quartis_info1 = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_mun v WHERE v.fk_ano_referencia = 3 and v.fk_variavel = $indc;";              
            $quartis_data1 = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_mun v WHERE v.fk_ano_referencia = 3 and v.fk_variavel = $indc ORDER BY valor;";                         
            break;
    }
    
    $resultado1 = $quantil->buildAndReturnQuintil($quartis_info1, $quartis_data1, $decimais, $indc, $spc);
    if(sizeof($resultado1) == 0)
    {
        $response["msg"] = "Não existe dados na tabela de valores para esse indicador";
        $response["status"] = "no_operation";
        echo json_encode($response);
        die();
    }
    
    $oldgroupsql = "SELECT id FROM classe_grupo WHERE fk_ano_referencia IS NULL AND fk_variavel = '$indc' AND espacialidade='$spc';";
    $r_oldgroupsql = pg_query($con->open(), $oldgroupsql) or die("Nao foi possivel executar a consulta!");
    $oldgroup = pg_fetch_object($r_oldgroupsql);
    if($oldgroup)
    {
        $remove_cls_sql =  "DELETE FROM classe WHERE fk_classe_grupo = '$oldgroup->id';";
        $r = pg_query($con->open(), $remove_cls_sql) or die("Nao foi possivel executar a consulta!");

        $remove_grp_sql =  "DELETE FROM classe_grupo WHERE id = $oldgroup->id";
        $r = pg_query($con->open(), $remove_grp_sql) or die("Nao foi possivel executar a consulta!");
    }  

   
    //CRIA CLASSE PARA TODOS OS ANOS
    $classes = array();
    foreach ($resultado1 as $json) 
    {
        $cls = json_decode($json);
        array_push($classes, $cls);
        $grp_id = $cls->id;
    }

    $grp_id =  str_replace(".","",strchr($grp_id,"."));
    $sql_grupo = "INSERT INTO classe_grupo(id, fk_ano_referencia, fk_variavel, espacialidade) VALUES ('$grp_id', NULL, '$indc','$spc');";
    $r = pg_query($con->open(), $sql_grupo) or die("Nao foi possivel executar a consulta!");

    foreach ($classes as $obj) 
    {    
        $c = BORDA_CIDADE;   
        $sql = "INSERT INTO classe(fk_classe_grupo, nome, maximo, minimo, cor_preenchimento, "
           . " cor_linha, largura_linha) VALUES ('$grp_id', '$obj->name', '$obj->max', '$obj->min', '$obj->color','$c' ,'0.35');"; 

        $r = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");   
    }
    
    $response["msg"] = "Quintil gerado com sucesso!";
    $response["status"] = "success";
    echo json_encode($response);    
}
else 
{
    //SOMENTE UM ANO SELECIONADO
    switch ($spc)
    {
        case Consulta::$ESP_ESTADUAL:
            $quartis_info = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_estado v WHERE v.fk_ano_referencia = $ano and v.fk_variavel = $indc;";              
            $quartis_data = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_estado v WHERE v.fk_ano_referencia = $ano and v.fk_variavel = $indc ORDER BY valor;";              
            break;
        case Consulta::$ESP_MUNICIPAL:
            $quartis_info = "SELECT COUNT(v.valor) as count ,ROUND(MAX(v.valor),3) as max ,ROUND(MIN(v.valor),3) as min FROM valor_variavel_mun v WHERE v.fk_ano_referencia = $ano and v.fk_variavel = $indc;";              
            $quartis_data = "SELECT ROUND(v.valor,3) AS valor FROM valor_variavel_mun v WHERE v.fk_ano_referencia = $ano and v.fk_variavel = $indc ORDER BY valor;";              
            break;
    }
    
    
    $resultado = $quantil->buildAndReturnQuintil($quartis_info, $quartis_data, $decimais, $indc);
    if(sizeof($resultado) == 0)
    {
        $response["msg"] = "Não existe dados na tabela de valores para esse indicador";
        $response["status"] = "no_operation";
        echo json_encode($response);
        die();
    }

    //remove o grupo anterior
    //obtem o grupo antigo
    $oldgroupsql = "SELECT id FROM classe_grupo WHERE fk_ano_referencia = '$ano' AND fk_variavel = '$indc' AND espacialidade='$spc' ;";
    $r_oldgroupsql = pg_query($con->open(), $oldgroupsql) or die("Nao foi possivel executar a consulta!");
    $oldgroup = pg_fetch_object($r_oldgroupsql);
    if($oldgroup)
    {
        $remove_cls_sql =  "DELETE FROM classe WHERE fk_classe_grupo = '$oldgroup->id';";
        $r = pg_query($con->open(), $remove_cls_sql) or die("Nao foi possivel executar a consulta!");

        $remove_grp_sql =  "DELETE FROM classe_grupo WHERE id = $oldgroup->id";
        $r = pg_query($con->open(), $remove_grp_sql) or die("Nao foi possivel executar a consulta!");
    }

    //cria a classe
    $classes = array();
    foreach ($resultado as $json) 
    {
        $cls = json_decode($json);
        array_push($classes, $cls);
        $grp_id = $cls->id;
    }


    $grp_id =  str_replace(".","",strchr($grp_id,"."));
    $sql_grupo = "INSERT INTO classe_grupo(id, fk_ano_referencia, fk_variavel,espacialidade) VALUES ('$grp_id', '$ano', '$indc', '$spc');";
    $r = pg_query($con->open(), $sql_grupo) or die("Nao foi possivel executar a consulta!");

    foreach ($classes as $obj) 
    {    
        $c = BORDA_CIDADE;   
        $sql = "INSERT INTO classe(fk_classe_grupo, nome, maximo, minimo, cor_preenchimento, "
           . " cor_linha, largura_linha) VALUES ('$grp_id', '$obj->name', '$obj->max', '$obj->min', '$obj->color','$c' ,'0.35');"; 

        $r = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");   
    }

     $response["msg"] = "Quintil gerado com sucesso!";
     $response["status"] = "success";
     echo json_encode($response);
}

?>
