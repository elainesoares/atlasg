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

    function loadDimensoes()  
    {  
        $minhaConexao = new Conexao();

		$con = $minhaConexao->open();
        
		$sql = "SELECT id, nome  
        FROM tema WHERE id_tema_superior IS NULL";

   		$sql .= " ORDER BY nome ";

		$q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  

        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
            	$json[] = Array('nome'=> $dados->nome, 'id'=> $dados->id);
            }
        }  
        return $json;
    }

    function loadTemas()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "SELECT id, nome, nivel, id_tema_superior  
        FROM tema";

        $sql .= " ORDER BY id_tema_superior, cod ";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();

        if(pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('nome'=> $dados->nome, 'id'=> $dados->id,'nivel' => $dados->nivel,'tema_superior'=>$dados->id_tema_superior);
            }
        }  
        return $json;
    }

    function loadIndicadores()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "select var.id,var.sigla,var.nomecurto,var.nomelongo from variavel var";

        $sql .= " ORDER BY nomecurto ";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('nome'=> $dados->nomecurto, 'id'=> $dados->id,'texto' => $dados->nomelongo,'sigla' => $dados->sigla);
            }  
        }  
        
        return $json;
    }

    function loadIndicadoresHasTema()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "SELECT fk_variavel,fk_tema FROM variavel_has_tema";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('var'=> $dados->fk_variavel, 'tema'=> $dados->fk_tema);
            }  
        }  
        
        return $json;
    }
    
    $json = new stdClass();
    $json = (object)Array('indicadores'=>loadIndicadores(),'temas'=>loadTemas(),'dimensoes'=>loadDimensoes(),'var_has_tema'=>loadIndicadoresHasTema());
   
    echo json_encode($json);
?>