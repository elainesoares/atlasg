<?php
    /* =================== Bloqueia o acesso direto pela url =================== */
    /*if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }*/

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
        
		$sql = "select mun.id,mun.nome,mun.fk_estado,est.fk_regiao,est.nome from municipio as mun left join estado as est on mun.fk_estado = est.id";

   		$sql .= " ORDER BY mun.nome ";

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

    function loadEstados()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "select id,nome,fk_regiao from estado order by nome";

        //$sql .= " ORDER BY nome ";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('n'=> $dados->nome, 'id'=> $dados->id,'r'=>$dados->fk_regiao);
            }  
        }  
        
        return $json;
    }

    function loadRegioes()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "select id,nome from regiao";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('n'=> $dados->nome, 'id'=> $dados->id);
            }  
        }  
        
        return $json;
    }
    
    function loadAreasTematicas()  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "SELECT ri.id as id, ri.nome as nome, count(rihas.fk_municipio) as tam FROM regiao_interesse ri INNER JOIN regiao_interesse_has_municipio rihas ON ri.id = rihas.fk_regiao_interesse GROUP BY ri.id, ri.nome;";

     
        
        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('n'=> $dados->nome, 'id'=> $dados->id, 'tam'=> $dados->tam);
            }  
        }  
        
        return $json;
    }
    
    

    $json = new stdClass();
    $json = (object)Array('estados'=>loadEstados(),'regioes'=>loadRegioes(),'areasTematicas'=>loadAreasTematicas());
   
    echo json_encode($json);
?>