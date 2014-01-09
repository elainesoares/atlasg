<?php
//ob_start("ob_gzhandler");
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        include '../erro.php';
        die();
    }
    /* =================== LER VALORES DA REQUISIÇÃO =============================== */
    
    $ano = (int) $_POST['ano'];
    $municipio = (int) $_POST['municipio'];
    $espac = $_POST['espac'];

    /* =================== LIMPA REQUISIÇÃO ======================================== */

    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
        if (eregi("^[0-9]+$", $ano) && eregi("^[0-9]+$", $municipio)) {
            include_once '../config/conexao.class.php';

            #instanciando o objeto
            $minhaConexao = new Conexao();

            #chamada ao metodo open que abra a conexao
            $con = $minhaConexao->open();

            //$show_query = "select var.nomecurto,varmun.valor from variavel as var left join valor_variavel_mun as varmun on var.id = varmun.fk_variavel where var.id = $varid and varmun.fk_municipio = $municipio and fk_ano_referencia = $ano";
            //$show_query = "select var.nomecurto,varmun.valor from variavel as var left join valor_variavel_mun as varmun on var.id = varmun.fk_variavel where var.id in (120,121,122,123) and varmun.fk_municipio = $municipio and fk_ano_referencia = $ano order by var.nomecurto";
            if($espac == 'municipio')
                $show_query = "select var.nomecurto,varmun.valor from variavel as var left join valor_variavel_mun as varmun on var.id = varmun.fk_variavel where var.id in (196,197,198,199) and varmun.fk_municipio = $municipio and fk_ano_referencia = $ano order by var.nomecurto";
                
            else if($espac == 'estado'){
                $show_query = "SELECT var.nomecurto,varest.valor 
                              FROM variavel as var LEFT JOIN valor_variavel_estado as varest ON var.id = varest.fk_variavel 
                              WHERE var.id IN (196,197,198,199) AND varest.fk_estado = $municipio AND fk_ano_referencia = $ano ORDER BY var.nomecurto";
            }
            
            $res = @pg_query($con, $show_query) or die("Nao foi possivel executar a consulta!");
                        
            while ($linha = pg_fetch_array($res)) 
                echo "$linha[0]|$linha[1]|";

            $minhaConexao->close();
        }     
?>
