<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
{
    header("Location: {$path_dir}404");
}

include_once("../../../../config/config_path.php");
include_once("../../../../config/config_gerais.php");
include_once("../../../../config/conexao.class.php");
include_once("../../consulta/Consulta.class.php");


$data = $_POST["data"];

$con = new Conexao();
$response = array();


foreach ($data as $obj)
{   
    
     $nome = $obj["nome"];
     $max = $obj["max"];
     $min = $obj["min"];
     $color = $obj["color"];
     $c_id = $obj["c_id"];

     
    $sql = "UPDATE classe SET  nome='$nome', maximo='$max', minimo='$min', cor_preenchimento='$color'  WHERE id='$c_id';";
    $r = pg_query($con->open(), $sql) or die("Nao foi possivel executar a consulta!");
    
}

$response["msg"] = "Dados atualizados com sucesso!";
$response["status"] = "success";
echo json_encode($response);

?>
