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
$response = array();

if($ano == "all")
{
    $sql = "SELECT cg.id as cg_id,c.id as c_id,c.nome,c.minimo,c.maximo,c.cor_preenchimento FROM classe_grupo cg INNER JOIN classe c " . 
           " ON c.fk_classe_grupo = cg.id WHERE fk_ano_referencia IS NULL AND fk_variavel = $indc AND espacialidade = $spc ORDER BY c_id DESC;";
}
else
{
    $sql = "SELECT cg.id as cg_id,c.id as c_id,c.nome,c.minimo,c.maximo,c.cor_preenchimento FROM classe_grupo cg INNER JOIN classe c " . 
           " ON c.fk_classe_grupo = cg.id WHERE fk_ano_referencia = $ano AND fk_variavel = $indc AND espacialidade = $spc ORDER BY c_id DESC;";  
}


$r = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");
while($obj = pg_fetch_object($r))
{
    array_push($response, $obj);
}

echo json_encode($response);
?>
