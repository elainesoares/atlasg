    <?php
    ini_set("display_errors", 0);
    require_once "config/config_path.php";
    require_once 'config/conexao.class.php';
    require_once MOBILITI_PACKAGE . "/consulta/bd.class.php";
    require_once MOBILITI_PACKAGE . "/ranking/ranking.class.php";

    $compTitle = "- Todo o Brasil";
    $ano_h = 3;
    $data = array();
    $ranking;
    if (isset($_POST["cross_data_ranking"])) {
        $str_json = str_replace("\\", "", $_POST["cross_data_ranking"]);
        $data = objectToArray(json_decode($str_json));
        foreach ($data as $do => $d) {
            if ($data[$do] == -1) {
                $data[$do] = null;
            }
        }
        extract($data);
        $donwload = false;
        if (isset($load_more))
            $load_more = true;
        else
            $load_more = false;
        if($_POST["cross_data_download"] == "true"){
            $donwload = true;
            $load_more = true;
        }
        $ano_h = $fk_ano;
        $ranking = new Raking($ordem_id, $ordem, $pag, $espc, $start, $estado, $estados_pos, $load_more,$donwload,$fk_ano);

        if ($espc == "estadual") {
            $compTitle = "- Todos os Estados";
        } else {
            if ($estado == 0) {
                $compTitle = "- Todo o Brasil";
            } else {
                $compTitle = "- " . $ranking->getEstadoNome();
            }
        }
    } else {
        $ranking = new Raking();
    }
    ?>
    <script src="com/mobiliti/ranking/script.ranking.js" type="text/javascript" charset="utf-8"></script>
    <form id="f_cross_data_ranking" method="post">
        <input type="hidden" name="cross_data_ranking" id="cross_data_ranking"/>
        <input type="hidden" name="cross_data_download" id="cross_data_download" value="false"/>
    </form>

    <div class="containerPage">
        <div class="containerTitlePage">
            <div class="titlePage">
                <div class="titletopPage" style="font-size: 52pt;">Ranking <?php echo $compTitle; ?><br /><br /><span style="font-size: 30pt" id="span_year_show"></span></div>
                <div style="float: right; margin-top: 38px">
                    <?php echo $ranking->writeButton(); ?>
                </div>
            </div>
        </div>   
    </div>
    <?php

    function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }

    $classBtn1 = "";
    $classBtn2 = "";

    $onClick1 = "sendData(-1,-1,-1,'municipal',1,0)";
    $onClick2 = "sendData(-1,-1,-1,'estadual',1,0)";
    if ($ranking->pEspc == 'municipal') {
        $classBtn1 = "active";
        $onClick1 = "";
    } else {
        $onClick2 = "";
        $classBtn2 = "active";
    }
    ?>
    <div class="tabbable inlineBlock"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="<?php echo $classBtn1; ?> abaRank" onclick="<?php echo $onClick1; ?>"><a href="#tab1" data-toggle="tab">Municipal</a></li>
            <li class="<?php echo $classBtn2; ?> abaRank" onclick="<?php echo $onClick2; ?>"><a href="#tab2" data-toggle="tab">Estadual</a></li>
        </ul>
        <div class="" style="float:right;margin-right: 20px;margin-top: -70px;"><span style="font-weight: bold; margin-left:0px;margin-top: 20px; width:44px;position: absolute">ANO</span> <?php $ranking->drawAnoSelect(); ?></div>
    </div>
    <div class="leftContentRank">
        <div class="btnsRank"><?php $ranking->drawSelect(); ?></div>
        
        <div class="btnsRank">Ordenado pelo <b><?php echo $ranking->nOrdem; ?></b></div>
    <?php echo $ranking->drawLegenda(); ?>
    </div>
        <?php
        $ranking->draw();
        ?>
    <div style="clear: both"></div>
    <script>
        var flag_ranking = -1;
        function ranking_year_slider_listener(event, data)
        {
            if(flag_ranking != -1)
                sendData(<?php echo "{$ranking->pOrdem_id},'{$ranking->pOrdem}'"; ?>,<?php echo "1,'{$ranking->pEspc}',{$ranking->pStart},"; ?>$("#selectEstados").val());
        }
        function sendData(ordem_id,ordem,pag,espc,start,estado){
            loadingHolder.show("Carregando...");
            if(ordem == null)
                json = '{"ordem_id":'+ordem_id+',"ordem":'+ordem+',"pag":'+pag+',"espc":"'+espc+',"start":"'+start+'","estado":'+$("#selectEstados").val()+',"estados_pos":"'+$("#holderRankEstados").val()+'","fk_ano":'+convertAno($("#ranking_year_slider").val())+'}';
            else
                json = '{"ordem_id":'+ordem_id+',"ordem":"'+ordem+'","pag":'+pag+',"espc":"'+espc+'","start":"'+start+'","estado":'+$("#selectEstados").val()+',"estados_pos":"'+$("#holderRankEstados").val()+'","fk_ano":'+convertAno($("#ranking_year_slider").val())+'}';
            $("#cross_data_ranking").val(json);
           $("#f_cross_data_ranking").submit();
        }

        function sendDataDownload(){
            $("#cross_data_download").val("true");
            $("#f_cross_data_ranking").attr("target","_blank");
            sendData(<?php echo "{$ranking->pOrdem_id},'{$ranking->pOrdem}'"; ?>,<?php echo "1,'{$ranking->pEspc}',{$ranking->pStart},"; ?>$("#selectEstados").val());
            $("#f_cross_data_ranking").attr("target","");
            $("#cross_data_download").val("false");
            loadingHolder.dispose();
        }

        $(document).ready(function(){
            $("#ranking_year_slider").bind("slider:changed", ranking_year_slider_listener);
            $("#ranking_year_slider").simpleSlider("setValue", convertAnoIDtoLabel(<?php echo $ano_h; ?>));
            $("#span_year_show").html("("+convertAnoIDtoLabel(<?php echo $ano_h; ?>)+")");
            flag_ranking = 0;
            $('#imgTab6').tooltip({html:true, delay: 500});
            $(".bolinhaRank").tooltip();
            $("#selectRankLimit").change(function(){
                sendData(<?php echo "{$ranking->pOrdem_id},'{$ranking->pOrdem}'"; ?>,<?php echo "1,'{$ranking->pEspc}',{$ranking->pStart},{$ranking->pEstado}"; ?>);
            });
            
            $("#selct_ano").change(function(){
                sendData(<?php echo "{$ranking->pOrdem_id},'{$ranking->pOrdem}'"; ?>,<?php echo "1,'{$ranking->pEspc}',{$ranking->pStart},{$ranking->pEstado}"; ?>);
            });
            $("#selectEstados").change(function(){
                sendData(<?php echo "{$ranking->pOrdem_id},'{$ranking->pOrdem}'"; ?>,<?php echo "1,'{$ranking->pEspc}',{$ranking->pStart},"; ?>$(this).val());
            });
            $(".button-carregar-mais").click(function(){
                $("#tr_load_more").remove();
                $(this).remove();
                loadingHolder.show("Carregando...");
                json = '{"ordem_id":<?php echo $ranking->pOrdem_id; ?>,"ordem":"<?php echo $ranking->pOrdem; ?>","pag":1,"espc":"<?php echo $ranking->pEspc; ?>","start":"<?php echo $ranking->pStart; ?>","estado":'+$("#selectEstados").val()+',"estados_pos":"'+$("#holderRankEstados").val()+'","load_more":"true","fk_ano":'+convertAno($("#ranking_year_slider").val())+'}';
                $("#f_cross_data_ranking input").val(json);
                $("#f_cross_data_ranking").submit();



                //            $.ajax({
                //                type: 'post',
                //                url:'com/mobiliti/ranking/load_more.php',
                //                data:{
                //                    'ordem_id':<?php echo $ranking->pOrdem_id; ?>,
                //                    'ordem':'<?php echo $ranking->pOrdem; ?>',
                //                    'pag': 1,
                //                    'espc':'<?php echo $ranking->pEspc; ?>',
                //                    'start':<?php echo $ranking->pStart; ?>,
                //                    'estado':<?php echo $ranking->pEstado; ?>,
                //                    'estados_pos':$("#holderRankEstados").val()
                //                },
                //                success: function(retorno){
                //                }
                //            });
            });
        })
    </script>