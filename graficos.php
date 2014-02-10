<?php
    require_once "com/mobiliti/services/URL.class.php";
    //    require_once "com/mobiliti/consulta/bd.class.php";
    ob_start();
?>

<script type="text/javascript">
    var geral;
    var lastClickedId_ = "1";
    
    $(document).ready(function()
    {
//        console.log('ready');
        $('#imgTab1').tooltip({html:true, delay: 500});
        $('#imgTab2').tooltip({html:true, delay: 500});
        geral = new Geral(readyGo_);
        setTimeout(function(){
            readyGo_();
//            consulta_();
        },100);
    });
    
    function readyGo_()
    {
//        console.log('readyGo_');
        $(".iconAtlasModel").hover(
        function(){
            newStr_ = "img/icons/"+$(this).attr("icon") + ".png";
            $(this).attr("src",newStr_);
        },function(){ 
            if('imgTab'+lastClickedId_ != $(this).attr("id")){
                $(this).attr("src",newStr_);
            }
        });
        changeAba_(1,$("#imgTab1"));
    }
    
    function changeAba_(switcher, t){
//        console.log('changeAba_');
        b = $("#imgTab"+lastClickedId_);
        $(b).addClass('gray_button');
        $(b).removeClass('blue_button');
        $(t).addClass('blue_button');
        $(t).removeClass('gray_button');
        
        $("#content .tab-pane").css("display","none");
        $("#tab"+switcher).css("display","block");
        lastClickedId_ = switcher;
        if(switcher == 1){
            $("#imgTab6").show();
            $(b).html('<img src="./img/icons/lines_gray.png">');
            $(t).html('<img src="./img/icons/scatter_plot_white.png">');
            $(t).tooltip('hide')
            $("#form1").show();
            $("#form2").show();
            $('#btnPrintMap').show();
            geral.setListenerIndicadores(bolha_listener_indicador);
            geral.setListenerLugares(bolha_listener_lugar);
            geral.removeIndicadoresExtras();
        }
        else if(switcher == 2){
            $("#imgTab6").hide();
            $(b).html('<img src="./img/icons/scatter_plot_gray.png">');
            $(t).html('<img src="./img/icons/lines_white.png">');
            $("#form1").hide();
            $("#form2").hide();
            $('#btnPrintMap').show();
            geral.setListenerIndicadores(linha_listener_indicador);
            geral.setListenerLugares(linha_listener_lugar);
            //geral.get
        }
//        else if(switcher == 3){
//            $("#imgTab6").hide();
//            $(b).html('<img src="./img/icons/table_gray.png">');
//            $(t).html('<img src="./img/icons/brazil_white.png">');
//            $("#form1").hide();
//            $("#form2").hide();
//            $('#btnPrintMap').hide();
//            geral.setListenerIndicadores(map_listener_indicador);
//            geral.setListenerLugares(map_listener_lugar);
//            //geral.get
//        }else if(switcher == 5){
//            graficoLinhas.consultar(196);
//        }
        geral.dispatchListeners('changetab');
    }
    
    function consulta_(){
//        console.log('consulta_');
        if (!Array.prototype.indexOf)
        {
            Array.prototype.indexOf = function(elt)
            {
                var len = this.length >>> 0;

                var from = Number(arguments[1]) || 0;
                from = (from < 0)
                    ? Math.ceil(from)
                : Math.floor(from);
                if (from < 0)
                    from += len;

                for (; from < len; from++)
                {
                    if (from in this &&
                        this[from] === elt)
                        return from;
                }
                return -1;
            };
        }
        var url = document.URL.split("/");
//        console.log('url => '+url);
        i = url.indexOf("atlasg");
        var cons = url[i+1];
//        console.log('cons => '+cons);
        if(cons == "graficos"){
            var cons = url[i+2];
        }
        else if(cons == "tabela"){
            var cons = url[i+2];
        }
        
        if(cons == ""){
            return;
        }
        
        if(typeof(cons) == "undefined" || cons == ""){
            return;
        }
        //loadingHolder.show("Carregando dados...");
        $.ajax({
            type: 'post',
            url:'com/mobiliti/util/AjaxConsultaPronta.php',
            data:{'consulta':cons},
            success: function(retorno){
                //loadingHolder.show("Carregando dados...");
                j = jQuery.parseJSON(retorno);
                geral.setLugares(j[0]);
                geral.setIndicadores(j[1]);
                var url = document.URL.split("/");
                i = url.indexOf("consulta");
                var cons = url[i+1];
                
                var id  = j[2].id;
                var ano = j[2].a;
                var ord = j[2].o;
                setOrdemUrl(id,ano,ord);

                if(cons == "mapa"){
                    changeAba(2,$("#imgTab2"));
                }else
                    changeAba(1,$("#imgTab1"));
            }
        });
    }
</script>

<div id="content">
    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage">Gráficos</div>
            </div>
            <div class="iconAtlas">
                <button type="button" name="" value="" class="gray_button small_bt" style="margin-right: 5px;" id="imgTab1"  data-original-title='Ver no gráfico de Bolhas' title data-placement='bottom' onclick="changeAba_(1,this);">
                    <img src="./img/icons/scatter_plot_gray.png">
                </button>
                <button type="button" name="" value="" class="gray_button small_bt" id="imgTab2" onclick="changeAba_(2,this);" data-original-title='Ver no gráfico de Linhas' title data-placement='bottom'>
                    <img src="./img/icons/lines_gray.png">
                </button>
            </div>
        </div>
    </div>
</div>
<div class="linhaDivisoria"></div>
<div id="content" style="height: 750px;">
    <div class="containerPageComponentes">
        <div class="tab-content" style="min-height: 500px">
            <div class="tab-pane active" id="tab1">
                <div class="topContent" id="topContentHolder"></div>
                <?php include_once('com/mobiliti/grafico/bolhas/ui.grafico-dispersao2.php'); ?>
            </div>

            <div class="tab-pane" id="tab2">
                <?php include_once('com/mobiliti/grafico/linhas/ui.graficos-linhas2.php'); ?>
            </div>

            <div class="tab-pane" id="tab3">
                <?php //include_once('com/mobiliti/histogram/ui.histogram.php'); ?>
            </div>

            <div class="tab-pane" id="tab4">
                <?php //include_once('com/mobiliti/graficos/ui.grafico-dispersao.php'); ?>
            </div>

            <div class="tab-pane" id="tab5">
                <?php //include_once('com/mobiliti/grafico/linhas/ui.grafico-linhas.php'); ?>
            </div>
        </div>
    </div>   
</div>


<?php
    $title = "Consulta";
    $meta_title = 'Consulta do Atlas de Desenvolvimento Humano 2013';
    $meta_description = 'Realize consultas sobre os indicadores socieconômicos brasileiros da localidade desejada, e visualize-os em tabelas e mapas.';
    $content = ob_get_contents();
    ob_end_clean();
    include "web/base.php";
?> 
