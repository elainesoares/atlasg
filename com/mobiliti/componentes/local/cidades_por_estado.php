<?php
    ini_set( "display_errors", 0);
    ob_start("ob_gzhandler");
    /* =================== Bloqueia o acesso direto pela url =================== */
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }

    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $estado = (int)$_GET['estado'];

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    header("Content-Type: text/html; charset=ISO-8859-1");  
  
    include_once('../../../../config/conexao.class.php');

    function loadCidades($value)  
    {  
        $minhaConexao = new Conexao();

		$con = $minhaConexao->open();
        $sql;
        //$sql = "select mun.id,mun.nome,mun.fk_estado,est.fk_regiao,est.nome from municipio as mun left join estado as est on mun.fk_estado = est.id";
		//select mun.id,mun.fk_estado,mun.nome||'('||est.uf||')' nome from municipio mun left join estado est on mun.fk_estado = est.id
        if($value == -1)
            $sql = "select mun.id,mun.fk_estado,mun.nome||'('||est.uf||')' nome, fk_regiao from municipio mun left join estado est on mun.fk_estado = est.id";
        else
            $sql = "select mun.id,mun.fk_estado,mun.nome||'('||est.uf||')' nome, fk_regiao from municipio mun left join estado est on mun.fk_estado = est.id where fk_estado = {$value}";



   		$sql .= " ORDER BY nome ";

		$q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  

        if( pg_num_rows($q) > 0 )
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
            	$json[] = Array('n'=> $dados->nome, 'id'=> $dados->id,'e'=> $dados->fk_estado,'r'=>$dados->fk_regiao);
            }
        }  
        return $json;
    }

    $json = new stdClass();
    $json = (object)Array('cidades'=>loadCidades($estado));
   
    echo json_encode($json);
?>