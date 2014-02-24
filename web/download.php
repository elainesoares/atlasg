<?php // require_once 'config/config_path.php'; ?>


<div class="contentPages" style="min-height: 800px;">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div id="download_titulo" class="titletopPage"></div> 
            </div>
        </div>   
        <div class="containerRawData">
            <img src="img/download.png" />
            <h1 class="textCenter" id="download_dadosBrutos"></h1>
            <h3 id="download_indicadores"></h3>
                <?php
                    if(@$_SESSION["lang"] == "en" /*|| @$_SESSION["lang"] == "es"*/){
                ?>		
                    <a href="<?php echo $downloadDadosBrutos[$_SESSION['lang']]?>"><button title="" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 31px; margin-left: 98px; margin-top: 104px;" id="download_buttonBaixe"></button></a>
                <?php
                    }
                    else if (@$_SESSION["lang"] == "pt"){
                ?>
                    <a href="<?php echo $downloadDadosBrutos[$_SESSION['lang']] ?>"><button title="" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 31px; margin-left: 98px; margin-top: 83px;" id="download_buttonBaixe"></button></a>
                <?php
                    }
                    else if (@$_SESSION["lang"] == "es"){
                ?>
                    <a href="<?php echo $downloadDadosBrutos[$_SESSION['lang']] ?>"><button title="" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 31px; margin-left: 98px; margin-top: 63px;" id="download_buttonBaixe"></button></a>
                <?php
                    }
                ?>
		</div>
        <div class="containerSelectData">
            <img src="img/downloadDadosSelecionados.png" />
            <h1 class="textCenter" id='download_dadosSelecionados'></h1>
            <h3 id="download_naoSeiIndicadores"></h3>
            <p id="download_cliqueConsulta"></p>
            <?php 
               // if($_SESSION["lang"] == "pt"){
            ?>
            <a href="<?php echo $path_dir.$ltemp; ?>/consulta/"><button title="" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 44px; margin-left: 115px; margin-top: 53px;" id="download_buttonConsulta"></button></a>
            <?php 
                //} 
                //else
            ?>
            <!--<a href="<?php echo $path_dir; ?>consulta/padrao"><button title="" type="button" name="" value="" class="blue_button big_bt"  style="padding: 5px 44px; margin-left: 115px; margin-top: 40px;" id="download_buttonConsulta">Consulta</button></a>-->
            
        </div>
        
    </div>
    <div class="clear"></div>
    <div id="alertTabela" style="margin-top: 30px; height: 20px;"></div>
</div>

<?php //echo EXIBIR_ALERTA_DOWNLOAD ?>
<script>
    $(document).ready(function(){
        var alerta = '<?=EXIBIR_ALERTA_DOWNLOAD?>';
        console.log(alerta);
        if(alerta){
            console.log('entrei');
            $("#alertTabela").html('<div class="alert alert-warnning"><button type="button" class="close" data-dismiss="alert" style="float: left">&times;</button> '+lang_mng.getString("msg_alerta_download")+'</div>');
        }
        
        $("#download_titulo").html(lang_mng.getString("download_titulo"));
        $("#download_dadosBrutos").html(lang_mng.getString("download_dadosBrutos"));
        $("#download_indicadores").html(lang_mng.getString("download_indicadores"));
        $("#download_buttonBaixe").html(lang_mng.getString("download_buttonBaixe"));
        $("#download_dadosSelecionados").html(lang_mng.getString("download_dadosSelecionados"));
        $("#download_naoSeiIndicadores").html(lang_mng.getString("download_naoSeiIndicadores"));
        $("#download_cliqueConsulta").html(lang_mng.getString("download_cliqueConsulta"));     
        $("#download_buttonConsulta").html(lang_mng.getString("download_buttonConsulta"));
        
        $("#download_buttonBaixe").attr("title",lang_mng.getString("download_buttonBaixe"));
    });
</script>
