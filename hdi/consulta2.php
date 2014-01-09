<?php
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }

    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    $ano = (int) $_POST['ano'];
    $estado = (int) $_POST['estado'];

    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    if (eregi("^[0-9]+$", $ano) && eregi("^[0-9]+$", $estado)) {
	include_once '../config/conexao.class.php';

	#instanciando o objeto
	$minhaConexao = new Conexao();

	#chamada ao metodo open que abra a conexao
	$con = $minhaConexao->open();

	//$show_query = "select var.nomecurto,varest.valor from variavel as var left join valor_variavel_estado as varest on var.id = varest.fk_variavel where var.id in (120,121,122,123) and varest.fk_estado = $estado and fk_ano_referencia = $ano order by var.nomecurto";
	$show_query = "select var.nomecurto,varest.valor from variavel as var left join valor_variavel_estado as varest on var.id = varest.fk_variavel where var.id in (196,197,198,199) and varest.fk_estado = $estado and fk_ano_referencia = $ano order by var.nomecurto";

	$res = @pg_query($con, $show_query) or die("Nao foi possivel executar a consulta!");

	//$linha = pg_fetch_array($res);
	//echo "$linha[0]|$linha[1]";

	while ($linha = pg_fetch_array($res)) 
		echo "$linha[0]|$linha[1]|";
		
	$minhaConexao->close();
    }
?>
