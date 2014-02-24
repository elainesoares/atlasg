<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
    header("Location: {$path_dir}404");
}

include_once("../../../../config/config_path.php");
include_once("../../../../config/config_gerais.php");
include_once("../../../../config/conexao.class.php");
include_once("../../consulta/Consulta.class.php");


$indc = $_POST["indicador"];
$ano  = $_POST["ano"];
$spc  = $_POST["espacialidade"];

$con = new Conexao();

if($ano == "all")
    $sql = "SELECT id FROM classe_grupo WHERE fk_ano_referencia IS NULL AND fk_variavel = '$indc' AND espacialidade='$spc' ;";
else 
    $sql = "SELECT id FROM classe_grupo WHERE fk_ano_referencia = '$ano' AND fk_variavel = '$indc' AND espacialidade='$spc' ;";

$r_oldgroupsql = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");
$oldgroup = pg_fetch_object($r_oldgroupsql);
if($oldgroup)
{
    $remove_cls_sql =  "DELETE FROM classe WHERE fk_classe_grupo = '$oldgroup->id';";
    $r = pg_query($con->open(), $remove_cls_sql) or die("Nao foi possivel executar a consulta!");

    $remove_grp_sql =  "DELETE FROM classe_grupo WHERE id = $oldgroup->id";
    $r = pg_query($con->open(), $remove_grp_sql) or die("Nao foi possivel executar a consulta!");
}


$r = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");
while($obj = pg_fetch_object($r))
{
    array_push($response, $obj);
}

$response["msg"] = "Quintil removido com sucesso!";
$response["status"] = "success";
echo json_encode($response);

?>
