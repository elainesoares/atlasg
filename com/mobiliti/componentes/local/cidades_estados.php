<?php
    /* =================== Bloqueia o acesso direto pela url =================== */
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }

    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    header("Content-Type: text/html; charset=ISO-8859-1");  
  
    include_once('../../../../config/conexao.class.php');

    function loadCidades()  
    {  
        $minhaConexao = new Conexao();

		$con = $minhaConexao->open();
        
		$sql = "select mun.id,mun.fk_estado,mun.nome||'('||est.uf||')' nome from municipio mun left join estado est on mun.fk_estado = est.id";

   		$sql .= " ORDER BY nome";

		$q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  

        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
            	$json[] = Array('nome'=> $dados->nome, 'id'=> $dados->id,'estado'=> $dados->fk_estado);
            }
        }  
        return $json;
    }

    function loadEstados()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "select id,nome from estado order by nome";

        //$sql .= " ORDER BY nome ";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('nome'=> mb_strtolower($dados->nome, 'UTF-8' ), 'id'=> $dados->id);
            }  
        }  
        
        return $json;
    }

    $json = new stdClass();
    $json = (object)Array('cidades'=>loadCidades(),'estados'=>loadEstados());
   
    echo json_encode($json);
?>