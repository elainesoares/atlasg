<?php require_once 'config/config_path.php'; ?>
<div class="contentPages" style="min-height: 800px;">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">Download</div> 
            </div>
        </div>   
        <div class="containerRawData">
            <img src="img/download.png" />
            <h1 class="textCenter"> Dados Brutos </h1>
            <h3> Eu quero todos os indicadores e todas as espacialidades disponíveis </h3>
            <a href="<?php echo BASE_COMPLETA_XLS ?>"><button title="Baixe Agora" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 31px; margin-left: 98px; margin-top: 83px;">Baixe Agora</button></a>
        </div>
        <div class="containerSelectData">
            <img src="img/downloadDadosSelecionados.png" />
            <h1 class="textCenter"> Dados Selecionados </h1>
            <h3> Eu ainda n&atilde;o sei as espacialidades e indicadores que quero </h3>
            <p> Clique em "Consulta", fa&ccedil;a sua sele&ccedil;&atilde;o e baixe o que quiser </p>
            <a href="<?php echo $path_dir; ?>consulta"><button title="Consulta" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 44px; margin-left: 115px; margin-top: 53px;">Consulta</button></a>
        </div>
        <div style="clear: both"></div><br />
        <div id="alertTabela"></div>
    </div>
</div>


<script>
    $(document).ready(function(){
        if(<?php echo EXIBIR_ALERTA_DOWNLOAD ?>){
            $("#alertTabela").html('<div class="alert alert-warnning"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo EXIBIR_ALERTA_DOWNLOAD_MENSAGEM.'_'.$S; ?></div>');
        }
    });
</script>
