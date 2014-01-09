<?php

if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
{
    include '../erro.php';
    die();
}
include_once '../config/conexao.class.php';
#instanciando o objeto
$minhaConexao = new Conexao();

#chamada ao metodo open que abra a conexao
$con = $minhaConexao->open();

$show_query = "select id,label_ano_referencia from ano_referencia order by label_ano_referencia desc";

$res = @pg_query($con, $show_query) or die("Nao foi possivel executar a consulta!");

echo "<option value=0>Escolha um ano</option>";
while ($linha = pg_fetch_array($res)) 
	echo "<option value=".$linha['id'].">".$linha['label_ano_referencia']."</option>";				
	
$minhaConexao->close();
?>
