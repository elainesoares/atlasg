<?php 
    include 'header.php';
    
    $lugares = str_replace("\\", "", $_POST["form1_lugares"]);
    $lugares = str_replace("'", "", $lugares);
    $indicadores = str_replace("\\", "", $_POST["form1_indicadores"]);
    $indicadores = str_replace("'", "", $indicadores);
?>
<script src="com/mobiliti/tabela/builder.tabela.js" type="text/javascript" charset="utf-8"></script>
<style>
    #body input{
        display:none !important;
    }
    .customRemoveColumn{
        display:none !important;
    }
    .customRemoveColumn2{
        display:none !important;
    }
    .customIconTabela{
        display:none !important;
    }
    #tbody_1{
        height: auto !important;
        overflow-y: auto !important;
        overflow-x: auto !important;
    }
</style>
<script>
    var tabelaImprimir = true;
    function tabela_build_download(){
        try{
            $.ajax({
                type: 'post',
                url:'com/mobiliti/tabela/tabela.controller.php',
                data:{'json_lugares':jQuery.parseJSON('<?php echo $lugares?>'),'json_indicadores' : jQuery.parseJSON('<?php echo $indicadores?>'),'json_search_names': true},
                success: function(retorno){
                    try{
                        tbObjetoConsulta = jQuery.parseJSON(retorno);
                        if(typeof(tbObjetoConsulta.erro) != 'undefined'){
                            return;
                        }
                        delete JSONOrder["0_0"];
                        setOrder(this,0,0,0);
                    }catch(e){
                    }
                }
            });
        }catch(e){
        }
    }
    
    $(document).ready(function(){
        tabela_build_download();
    });
</script>
<body style="background: #FFF" id="body">
    <?php
        include "ui.tabela.imprimir.php";
    ?>
</body>