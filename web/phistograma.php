<?php 
    /* =================== LER VALORES DA REQUISIÇÃO =============================== */    
    $NumConsulta = (int) $_GET["consulta"];

    /* =================== LIMPA REQUISIÇÃO ======================================== */
    $_GET = null;
    $_POST = null;
    $_REQUEST = null;

    /* =================== FECHA LEITURA DAS VARIÁVEIS E LIMPEZA DA REQUISIÇÃO ===== */
    ob_start();
    
    $labelBtnEnviar = "Executar Consulta"; //Label do botão de execução
    include_once('config/conexao.class.php'); 
?>
<!-- filtros da consulta -->
<script type="text/javascript" src="comp_consulta/filtro_unidades.js"></script>
<script type="text/javascript" src="comp_consulta/filtro_tema_var_box.js"></script>
<script type="text/javascript" src="comp_consulta/filtro_palavrachave_var.js"></script>
<script type="text/javascript" src="comp_consulta/filtro_reseta_combo.js"></script>

<!-- js generico -->
<script type="text/javascript" src="comp_consulta/comp_js.js"></script>

<!-- treeview - árvore tema e subtema -->
<link rel="stylesheet" href="css/jquery.treeview.css" />
<script src="js/jquery.treeview.js" type="text/javascript"></script>  

<!-- form layout -->
<script src="comp_consulta/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="comp_consulta/uniform.default.css" type="text/css" media="screen">

<!-- Conteúdo da consulta -->
<div id="container">
    <div class="content_area" name="consulta" id="consulta">
        <div class="selecao">
            <h1>HISTOGRAMA</h1>
            <p>Crie um gráfico de barras com a distribuição das frenquências para qualquer uma das variáveis.</p><br />
            <?php
                #instanciando o objeto
                $minhaConexao = new Conexao();

                #chamada ao metodo open que abra a conexao
                $con = $minhaConexao->open();

                include_once('comp_consulta/comp_unidades.php'); //Componente de seleção das unidades espaciais
                include_once('comp_consulta/comp_grafico_histograma.php'); //Componente de seleção dos temas, subtemas e tabelas
                include_once('comp_consulta/comp_ano_radio.php'); //Componente de seleção dos anos que serão consultados
            ?>

        </div>
    </div>
</div>

<?php
    $content = ob_get_contents();
    $title = "Consulta";
    ob_end_clean();
    include "base.php";
?>