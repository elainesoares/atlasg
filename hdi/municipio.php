<?php
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }
    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    $estado = (int) $_POST['estado'];

    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    if(eregi("^[0-9]+$", $estado)) {
	include_once '../config/conexao.class.php';
	#instanciando o objeto
	$minhaConexao = new Conexao();

	#chamada ao metodo open que abra a conexao
	$con = $minhaConexao->open();

	$show_query = "select id, nome from municipio where fk_estado = $estado order by nome asc";

	$res = @pg_query($con, $show_query) or die("Nao foi possivel executar a consulta!");

	//echo "<option value=0>Escolha um munic&iacutepio</option>";
	echo "<option value=0>-------</option>";
	while ($linha = pg_fetch_array($res)) 
		echo "<option value=".$linha['id'].">".$linha['nome']."</option>";
		
	$minhaConexao->close();
    }
?>
