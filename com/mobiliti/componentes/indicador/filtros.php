<?php
    /* =================== Bloqueia o acesso direto pela url =================== */
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }

    
    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    /* =================== LIMPA REQUISIÇÃO ======================================== */
    
    $user_lang = $_GET['user_lang'];
    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    header("Content-Type: text/html; charset=ISO-8859-1");  
  
    include_once('../../../../config/conexao.class.php');

    function loadDimensoes($user_lang)  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        $sql = "SELECT t.id, lg.nome FROM tema t INNER JOIN lang_tema lg ON lg.fk_tema = t.id WHERE t.id_tema_superior IS NULL AND lg.lang='" . $user_lang . "' ";
        $sql .= " ORDER BY t.cod ASC; ";

        
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

    function loadTemas($user_lang)  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "SELECT t.id, lg.nome, t.nivel, t.id_tema_superior  FROM tema t INNER JOIN lang_tema lg ON lg.fk_tema = t.id WHERE lg.lang ='" . $user_lang . "' ";

        $sql .= " ORDER BY id_tema_superior, cod ";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();

        if(pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                $json[] = Array('n'=> $dados->nome, 'id'=> $dados->id,'nivel' => $dados->nivel,'tema_superior'=>$dados->id_tema_superior);
            }
        }  
        return $json;
    }

    function loadIndicadores($user_lang)  
    {  
        $minhaConexao = new Conexao();

        $con = $minhaConexao->open();
        
        $sql = "select var.id, var.sigla, lg.nomecurto, lg.nomelongo from variavel var inner join lang_var lg on var.id = lg.fk_variavel where lg.lang = '" . $user_lang . "' ";

        $sql .= " ORDER BY var.ordem ASC";

        $q = pg_query($con, $sql) or die("Nao foi possivel executar a consulta!");
        
        $json = Array();  
        
        if( pg_num_rows($q) > 0 )  
        {  
            while( $dados = pg_fetch_object($q) )  
            {    
                // $json[] = Array('nome'=> $dados->nomecurto, 
                //     'id'=> $dados->id,
                //     'texto' => $dados->nomelongo,
                //     'sigla' => $dados->sigla,
                //     'nc' => $dados->nomecurto,
                //     'desc' => $dados->nomelongo);

                 $json[] = Array('id'=> $dados->id,
                    'sigla' => $dados->sigla,
                    'nc' => $dados->nomecurto,
                    'desc' => $dados->nomelongo);
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
                $json[] = Array('variavel'=> $dados->fk_variavel, 'tema'=> $dados->fk_tema);
            }  
        }  
        
        return $json;
    }
    
    $json = new stdClass();
    $json = (object)Array('indicadores'=>loadIndicadores($user_lang),'temas'=>loadTemas($user_lang),'dimensoes'=>loadDimensoes($user_lang),'var_has_tema'=>loadIndicadoresHasTema());
   
    echo json_encode($json);
?>