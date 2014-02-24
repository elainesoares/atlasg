<?php
require_once "com/mobiliti/services/URL.class.php";
//    require_once "com/mobiliti/consulta/bd.class.php";
ob_start();
//$widthStyle = "page100Percent";
//    $bd = new bd();
//    $sql = "select * from valor_variavel_mun where fk_ano_referencia = 3 and fk_municipio = 995 order by fk_municipio, fk_variavel limit 500";
//    $result = $bd->ExecutarSQL($sql);
//    $i3 = array();
//    $i2 = array();
//    $i = array();
//    foreach($result as $key=>$val){
//        $v = $val["valor"];
//        if(strpos($v, ".")){
//            $arr = explode(".",$v);
//            if($arr[0] == "0"){
//                $i3[] = $val["fk_variavel"];
//            }else{
//                $i2[] = $val["fk_variavel"];
//            }
//        }else{
//            $i[] = $val["fk_variavel"];
//        }
//    }
//    echo "(".  implode(",", $i3).")";
//    echo "(".  implode(",", $i2).")";
//    echo "(".  implode(",", $i).")";

$useragent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'IE';
} elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Opera';
} elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Firefox';
} elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Chrome';
} elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
    $browser_version = $matched[1];
    $browser = 'Safari';
} else {
    // browser not recognized!
    $browser_version = 0;
    $browser = 'other';
}

$version = $browser_version;
if (strpbrk($browser_version, ".")) {
    $sp = explode('.', $browser_version);
    $version = $sp[0];
}
foreach ($NavegadoresBloqueados as $key => $v) {
    if ($browser == $key) {
        if ($v >= (int) $version) {
            include 'include_block.php';
        }
    }
}
//  print "browser: $browser $browser_version";
?>

<script type="text/javascript">
    //=========================================================================
    //Geral
    //=========================================================================
    var geral;
    var limites;

    var lastClickedId = "1";
    $(document).ready(function()
    {
        //        alert('ready');
        $('#imgTab1').tooltip({html:true});
        $('#imgTab2').tooltip({html:true});
        $('#imgTab3').tooltip({html:true, delay: 500});
        $('#imgTab6').tooltip({html:true, delay: 500});
        $('#form1').tooltip({html:true, delay: 500});
        $('#btnPrintMap').tooltip({html:true, delay: 500});
        
        
        $("#btnPrintMap").attr("data-original-title",lang_mng.getString("mapa_imprimir"));
        $("#imgTab2").attr("data-original-title",lang_mng.getString("mapa_ver"));
        $("#imgTab1").attr("data-original-title",lang_mng.getString("tabela_ver"));
        $("#imgTab6").attr("data-original-title",lang_mng.getString("download_text"));
        
        
        $("#id_title_consulta").html(lang_mng.getString("home_titleConsulta"));
        $('#btnPrintMap').hide();
        
        geral = new Geral(readyGo);
        setTimeout(function(){
            readyGo();
            consulta();
        },100);
    });

    function readyGo()
    {
        //        alert('readyGo');
        $(".iconAtlasModel").hover(
        function(){
            //                newStr = "img/icons/"+$(this).attr("icon") + "down.png";
            newStr = "img/icons/"+$(this).attr("icon") + ".png";
            $(this).attr("src",newStr);
        },function(){
            if('imgTab'+lastClickedId != $(this).attr("id")){
                //                    newStr = "img/icons/"+$(this).attr("icon") + ".png"; 
                $(this).attr("src",newStr);
            }
        });
        //tabela_build('jso');
        changeAba(1,$("#imgTab1"));
    }

    function changeAba(switcher, t){
        //        alert('lastClickedId ' + lastClickedId);
        //        alert('switcher ' + switcher);
        //        alert('t ' + t);
        b = $("#imgTab"+lastClickedId);
        $(b).addClass('gray_button');
        $(b).removeClass('blue_button');
        $(t).addClass('blue_button');
        $(t).removeClass('gray_button');
        
        $("#content .tab-pane").css("display","none");
        $("#tab"+switcher).css("display","block");
        //        $('#imgTab'+lastClickedId).attr("src","img/icons/"+$('#imgTab'+lastClickedId).attr('icon')+".png");
        //        $('#imgTab'+switcher).attr("src","img/icons/"+$('#imgTab'+switcher).attr('icon')+"down.png");
        lastClickedId = switcher;
        if(switcher == 1){
            $("#imgTab6").show();
            $(b).html('<img src="./img/icons/brazil_gray.png">');
            $(t).html('<img src="./img/icons/table_white.png">');
            $(t).tooltip('hide')
            $("#form1").show();
            $("#form2").show();
            $('#btnPrintMap').hide();
            geral.setListenerIndicadores(listnerTabelaIndicadores);
            geral.setListenerLugares(listnerTabelaLocal);
            geral.removeIndicadoresExtras();
        }else if(switcher == 2){
            $("#imgTab6").hide();
            $(b).html('<img src="./img/icons/table_gray.png">');
            $(t).html('<img src="./img/icons/brazil_white.png">');
            $("#form1").hide();
            $("#form2").hide();
            $('#btnPrintMap').show();
            geral.setListenerIndicadores(map_listener_indicador);
            geral.setListenerLugares(map_listener_lugar);
            //geral.get
        }else if(switcher == 3){
            $("#imgTab6").hide();
            $(b).html('<img src="./img/icons/table_gray.png">');
            $(t).html('<img src="./img/icons/brazil_white.png">');
            $("#form1").hide();
            $("#form2").hide();
            $('#btnPrintMap').hide();
            geral.setListenerIndicadores(map_listener_indicador);
            geral.setListenerLugares(map_listener_lugar);
            //geral.get
        }else if(switcher == 5){
            graficoLinhas.consultar(196);
        }
        geral.dispatchListeners('changetab');
    }

    function novaPagina(sw){
        t_geral = false;
        g =  geral.getLugares();

        for(var i in g){
            if(g[i].l.length > 0){
                t_geral = true;
                break;
            }
        }
        if(!t_geral){
            $("#alertTabela").html('<div class="alert alert-warnning"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $lang_mng->getString("msg_down01"); ?></div>');
            return;
        }
        if(geral.getIndicadores().length == 0){
            $("#alertTabela").html('<div class="alert alert-warnning"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $lang_mng->getString("msg_down01"); ?></div>');
            return;
        }
        if(<?php echo EXIBIR_ALERTA_DOWNLOAD ?>){
            $("#alertTabela").html('<div class="alert alert-warnning"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $lang_mng->getString("msg_alerta_download"); ?></div>');
        }
        if(sw == 1){
            $("#form1_lugares").val(JSON.stringify(geral.getLugares()));
            $("#form1_indicadores").val(JSON.stringify(geral.getIndicadores()));
            $("#form1").submit();
        }else if(sw == 2){
            result = getCSV();
            $("#form2_lugares").val(result);
            $("#form2").submit();
        }
    }
    
    
    function printMapEvt()
    {
        $("#form_mapa_print").submit();
    }
    
    function consulta(){
        if (!Array.prototype.indexOf)
        {
            Array.prototype.indexOf = function(elt /*, from*/)
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
        i = url.indexOf("consulta");
        var cons = url[i+1];
        if(cons == "mapa"){
            //            changeAba(2);
            var cons = url[i+2];
        }else if(cons == "tabela")
            var cons = url[i+2];
        
        if(cons == ""){
            return;
        }
        
        if(typeof(cons) == "undefined" || cons == ""){
            return;
        }
        loadingHolder.show(lang_mng.getString("carregando"));
        $.ajax({
            type: 'post',
            url:'com/mobiliti/util/AjaxConsultaPronta.php',
            data:{'consulta':cons},
            success: function(retorno){
                loadingHolder.show(lang_mng.getString("carregando"));
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
                <div id="id_title_consulta" class="titletopPage"></div>
            </div>
            <div class="iconAtlas">
                <button type="button" name="" value="" class="gray_button small_bt" style="margin-right: 5px;" id="imgTab1"  data-original-title='Ver na tabela' title data-placement='bottom' onclick="changeAba(1,this);">
                    <img src="./img/icons/table_gray.png">
                </button>
                <button type="button" name="" value="" class="gray_button small_bt" id="imgTab2" onclick="changeAba(2,this);" data-original-title='Ver no mapa' title data-placement='bottom'>
                    <img src="./img/icons/brazil_gray.png">
                </button>
                <!--                <button type="button" name="" value="" class="gray_button small_bt" id="imgTab3" onclick="changeAba(3,this);" data-original-title='Ver no Histograma' title data-placement='bottom'>
                                    <img src="./img/icons/bars_gray.png">
                                </button>-->
                <div class="inIconAtlas">


                    <form method="post" action="<?php echo @$_SESSION['lang'] ?>/imprimir_mapa/" target="_blank" id="form_mapa_print">

                        <div class="btn_print" data-original-title='Imprimir mapa.' id='btnPrintMap' style="float:right; margin-top: 0px;">  
                            <button type="button" class="gray_button big_bt" onclick="printMapEvt()" >     
                                <img  src="img/icons/print_gray.png"/>
                            </button>    
                        </div>

                        <input type="hidden" id="p_ano" name="p_ano" value="" />
                        <input type="hidden" id="p_indicador" name="p_indicador" value="" />
                        <input type="hidden" id="p_map" name="p_map" value="" />
                        <input type="hidden" id="p_legend" name="p_legend" value="" />
                        <input type="hidden" id="p_selection" name="p_selection" value="" />
                        <input type="hidden" id="p_nome_local" name="p_nome_local" value="" />
                        <input type="hidden" id="p_title" name="p_title" value="" />
                        <input type="hidden" id="p_value_idh" name="p_value_idh" value="" />
                        <input type="hidden" id="p_value_longevidade" name="p_value_longevidade" value="" />
                        <input type="hidden" id="p_value_renda" name="p_value_renda" value="" />
                        <input type="hidden" id="p_value_educacao" name="p_value_educacao" value="" />

                    </form>

                    <form method="post" action="consulta/download/" target="_blank" id="form2">
                        <input type="hidden" id="form2_lugares" name="form2_lugares" value="" />
                        <input type="hidden" id="form2_indicadores" name="form2_indicadores" value="" />
                    </form>
                        <button class="gray_button big_bt" id="imgTab6" data-original-title='DOWNLOAD_TEXT' title data-placement='bottom' icon="download_2" onclick="novaPagina(2)">
                            <img src="img/icons/download_2.png"/>
                        </button>

                </div>
            </div>
        </div>
        <div id="alertTabela"></div>
    </div>
</div>
<div class="linhaDivisoria">
</div>
<div id="content">
    <div class="containerPageComponentes">
        <div class="tab-content" style="min-height: 500px">
            <div class="tab-pane active" id="tab1">
                <div class="topContent" id="topContentHolder">
                </div>
                <?php include_once('com/mobiliti/tabela/ui.tabela.php'); ?>
            </div>

            <div class="tab-pane" id="tab2">
                <?php include_once('com/mobiliti/map/ui.map.php'); ?>
            </div>

            <div class="tab-pane" id="tab3">
                <?php include_once('com/mobiliti/histogram/ui.histogram.php'); ?>
            </div>

            <div class="tab-pane" id="tab4">
                <?php include_once('com/mobiliti/graficos/ui.grafico-dispersao.php'); ?>
            </div>

            <div class="tab-pane" id="tab5">
                <?php include_once('com/mobiliti/grafico/linhas/ui.grafico-linhas.php'); ?>
            </div>
        </div>
    </div>   
</div>

<?php

$title = $lang_mng->getString("home_titleConsulta");
$meta_title = $lang_mng->getString("meta_title");
$meta_description = $lang_mng->getString("meta_description"); 

$content = ob_get_contents();
ob_end_clean();
include "web/base.php";
?> 
