<?php
if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
{
    include '../erro.php';
    die();
}
    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    $municipio = (int) $_POST['municipio'];

    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    if(eregi("^[0-9]+$", $municipio)) {
	include_once '../config/conexao.class.php';
	#instanciando o objeto
	$minhaConexao = new Conexao();

	#chamada ao metodo open que abra a conexao
	$con = $minhaConexao->open();

	$show_query = "select id,fk_estado from municipio where geocodmun = $municipio";

	$res = @pg_query($con, $show_query) or die("Nao foi possivel executar a consulta!");

	$linha = pg_fetch_array($res);
	echo "$linha[0]|$linha[1]";
	
	//echo "<option value=0>Escolha um estado</option>";
	//while ($linha = pg_fetch_array($res)) 
	//	echo "<option value=".$linha['id'].">".$linha['nome']."</option>";				
        
	$minhaConexao->close();
    }
?>
