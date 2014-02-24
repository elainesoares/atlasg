<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>--> <!-- Está dando conflito -->

<!-- <link rel="stylesheet" type="text/css" href="<?php //echo $path_dir ?>css/histogramStyle.css"> -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="com/mobiliti/histogram/drawChart.js"></script>
<script type="text/javascript">
//    var histogram_e = null;
//    var histogram_l = null;
//    var histogram_i = null;
//    var histogram_i_name = "";
//    var histogram_a = 3;
//    var histogram_s = null;
//    var histogram_legend_is_visible = false;
//    var histogram_legend_button_is_lock = false;
//    var histogram_width  = 681;
//    var histogram_height = 600;
//    var HISTOGRAM_TOOL_INFO = 1; 
//    var HISTOGRAM_TOOL_SELC = 2; 
//    var histogram_tool = MAP_TOOL_INFO;
//    var histogram_actual_canvas = 2;
//    var histogram_use_interpolation = false;
//    var histogram_legend_is_visible = false;
//    var histogram_clicked_away = false;
//    var histogram_extent = "-77.12 -38.98 -29.15 8.99";
//    var histogram_max_extent = "-77.12 -38.98 -29.15 8.99";
//    var local2;
//    var histogram_indc;
//    var histogram_indc_selector;
//    var ___first_time_year_2 = true;
//    var zoom_step2 = 3;
//    var PAN_STEP2  = 2;
//    $(document).ready(function(){
//        $('#local_box2').load('com/mobiliti/componentes/local/local.html',function(){
////            alert('local_box2');
//            local2 = new SeletorLocal();
//            local2.startLocal(listenerLocalHistogram,"local_box2",false);
//            histogram_indc = new LocalSelector();
//            local2.setButton(histogram_indc.html('uihistogramindicator_selector'))
//            histogram_indc.startSelector(true,"uihistogramindicator_selector",histogram_indcator_selector_histogram,"right");
//        });
//            
//        $('#box_indicador_local2').load('com/mobiliti/componentes/local_indicador/indicador.html',function(){
////            alert('box_indicador_local2');
//            indicadorLocal2 = new SeletorIndicador();
//            indicadorLocal2.startLocal(listenerLocalIndicadores2,"box_indicador_local2",false);
//            try{
//                histogram_indc_selector = new IndicatorSelector();
//                indicadorLocal2.setButton(histogram_indc_selector.html('histogramEditIndicador'));
//                histogram_indc_selector.startSelector(true,"histogramEditIndicador",seletor_indicador2,"right");
//            }catch(e){
//                    //erro
//            }
//        });
//        histogram_init();
//    });
//    
//    function listenerLocalHistogram(lugares){
////        alert('listenerLocalHistogram');
//        geral.setLugares(lugares);
//    }
//    
//    function histogram_indcator_selector_histogram(array){
////        alert('histogram_indcator_selector_histogram');
//        local.setItensSelecionados(array);
//    }
//    
//    function listenerLocalIndicadores2(indicadores){
////        alert('listenerLocalIndicadores2');
//        geral.setIndicadores(indicadores);
//        histogram_indc_selector.refresh();
//    }
//    
//    function seletor_indicador2(obj){
////        alert('seletor_indicador2');
//        geral.setIndicadores(obj);
//        indicadorLocal2.refresh();
//    }
//    
//    function histogram_hide_selection(){
//        $('#uihistogrampixel').popover('hide');
//        $('#uihistogrampixel').hide();
//        $("#uihistogramselection").hide();
//        $("#miniperfil_idh").html('0');
//        $("#miniperfil_longevidade").html('0');
//        $("#miniperfil_renda").html('0');
//        $("#miniperfil_educacao").html('0');  
//        $("#uihistogram_popover_perfil_link").hide();
//        return 0;
//    }
//    
//    function histogram_loading(status){
////        alert('map_loading');
//        if(status)
//            $("#uihistogramloader").show();
//        else
//            $("#uihistogramloader").hide();	
//    }
//    
//    function histogram_load(e,l,i,a,contrast,colors,callout,zoom,istool){
////        alert('histogram_load');
//        histogram_loading(true);
//        var histogram_data = new Object();
//        
//        //define os ids
//        histogram_data['e'] = e; // espacialidade
//        if(l.length)
//            histogram_data['l'] = l.toString(); // array de locais em modo texto     
//        else
//            histogram_data['l'] = new Array(0);
//        histogram_data['i'] = i; // indicador
//        histogram_data['a'] = a; // ano
//        
//        histogram_e = e;  
//        histogram_l = l; 
//        histogram_i = i; 
//        histogram_a = a; 
//        histogram_s = contrast;
//        
//        histogram_data['height'] = histogram_height;
//        histogram_data['width'] = histogram_width;
//        histogram_data['extent'] = histogram_extent;
//        histogram_data['istool'] = istool;
//        
//        if(zoom){
//            histogram_data['zoom_extent'] = $("#zoom_extent").val();
//        }
//        else{
//            histogram_data['zoom_extent'] = null;
//        }
// 
//        histogram_data['contrast'] = contrast;
//        histogram_data['colors'] = colors;
//        histogram_data['callout'] = callout;
//        
//        $.ajax({
//	type: "POST",
//	url: "<?php echo $path_dir ?>com/mobiliti/histogram/histogram.controller.php",
//	data: histogram_data,
//	success: histogram_response
//        });
//    }
//    
//    function histogram_year_slider_listener (event, data){
////        alert('histogram_year_slider_listener');
//        if(___first_time_year_2){
//            ___first_time_year_2 = false;
//            return;
//        }
//        
//        histogram_hide_selection(); 
//        
//        if(data.value === 1991)
//            histogram_a = 1;
//        else if(data.value === 2000)
//            histogram_a = 2;
//        else if(data.value === 2010)
//            histogram_a = 3;
//         
//        histogram_use_interpolation = true;
//           
//        //-----------------------------------
//        // Muda todos os anos dos indicadores
//        //-----------------------------------
//        var _indicadores = geral.getIndicadores();
//        for ( var i = 0 ; i < _indicadores.length ; i++ ){
//            geral.updateIndicador(i,histogram_a);
//        }
//        // ----------------------------------
//         
//        histogram_load(histogram_e,histogram_l,histogram_i,histogram_a,histogram_s,null,null,false,true); 
//    }
//    
//    function uihistogramselection_click_evt(e){
//        histogram_click_evt(e,this);
//        return 0;
//    }
//    
//    function histogram_positionate_pin(px_lat, px_lon,loader){
//        $("#lat").val(px_lat);
//        $("#lon").val(px_lon);           
//            
//        $("#uihistogrampixel").css("left",(px_lon - 7));
//        $("#uihistogrampixel").css("top",(px_lat - 10));  
//        
//        return 0;
//    }
//    
//    function histogram_popover_request(_spac,_px_lat,_px_lon,_extent){
//        var request_data = $.parseJSON( '{"spac":"", "px_lat":0, "px_lon":0, "extent":"", "height":0, "width":0, "selection":true, "indc":"" , "year":"" }' );
//        request_data.spac   =  _spac;
//        request_data.px_lat =  _px_lat;
//        request_data.px_lon =  _px_lon;
//        request_data.extent =  _extent;
//        request_data.height =  histogram_height;
//        request_data.width  =  histogram_width;
//        request_data.indc = histogram_i;
//        request_data.year = histogram_a;
//        
//        $.ajax({
//            type: "POST",
//            url: "<?php echo $path_dir ?>com/mobiliti/histogram/histogram.spatialquery.service.php",
//            data: request_data,
//            success: histogram_popover_response
//        });
//        return 0;
//     }
//    
//    function histogram_build_popover_content(px_lat, px_lon){
//        var result = $.parseJSON('{"title":"", "arvore":true, "grafico":true, "perfil":false}' );
//        $("#uihistogram_popover_idh_tree").hide();  
//        $("#uihistogram_popover_chart").hide();  
//        $("#uihistogram_popover_perfil_link").hide();  
//        if(histogram_e === 2)$("#uihistogram_popover_perfil_link").show();      
//        histogram_popover_request(histogram_e,px_lat,px_lon,histogram_extent);
//        return 0;
//    } 
//    
//    function histogram_click_evt(e,histogram){
//        e.preventDefault();
//        var offset = $(histogram).offset();
//            
//        var px_lat = (e.pageY - offset.top);
//        var px_lon = (e.pageX - offset.left);
//            
//        if(histogram_tool == MAP_TOOL_INFO){
//            $("#uihistogrampixel").show();
//            $("#uihistogrampixel").attr("src","<?php echo $path_dir ?>img/histogram/ajax-pin-loader.gif");
//      
//            histogram_positionate_pin(px_lat, px_lon,true);
//            histogram_build_popover_content(px_lat, px_lon);    
//        }
//        return 0;
//    }
//    
//    function histogram_selection_event(img, selection){
//        if (!selection.width || !selection.height)return;
//        $('#zoom_extent').val(selection.x1 + " " + selection.y1 + " " + selection.x2 + " " + selection.y2);
//        histogram_load(histogram_e,histogram_l,histogram_i,histogram_a,histogram_s,null,null,true,true);
//        return 0;
//    }
//    
//    function uihistogramtool_selectregion_event(event){
//        histogram_hide_selection();
//        if(histogram_tool === HISTOGRAM_TOOL_INFO){
//            histogram_tool = HISTOGRAM_TOOL_SELC;
//            $('#uihistogramcanvas_1').imgAreaSelect({autoHide:true, handles: true, hide: false, disable: false, onSelectEnd: histogram_selection_event });
//            $('#uihistogramcanvas_2').imgAreaSelect({autoHide:true, handles: true, hide: false, disable: false, onSelectEnd: histogram_selection_event });
//        }
//        else{
//            histogram_tool = HISTOGRAM_TOOL_INFO;
//            $('#uihistogramcanvas_1').imgAreaSelect({hide: true, disable: true});
//            $('#uihistogramcanvas_2').imgAreaSelect({hide: true, disable: true});
//        }
//    }
//    
//    function histogram_init(){
////        alert('histogram_init');
//        $('.nav-tabs').button();
//        $("#histogram_year_slider").bind("slider:changed", histogram_year_slider_listener);
//        
//        $("#uihistogramselection").css("min-height", histogram_height + "px");
//        $("#uihistogramselection").css("min-width", histogram_width + "px");
//        
//        $("#uihistogramcanvas_1").css("min-height", histogram_height + "px");
//        $("#uihistogramcanvas_1").css("min-width", histogram_width + "px");
//                
//        $("#uihistogramcanvas_2").css("min-height", histogram_height + "px");
//        $("#uihistogramcanvas_2").css("min-width", histogram_width + "px");
//               
//        $("#uihistogramselection").click(uihistogramselection_click_evt);
//        
//        $("#uihistogramtool_selectregion").click(uihistogramtool_selectregion_event);
//        $("#uihistogramtool_zoomout").click(uihistogramtool_zoomout_event);
//
//        histogram_loading(false);
//        
//        //-----------------------------------
//        $("#uihistogramtool_info").addClass('active');
//        //---------------------------------------
//        
//        $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase()); 
//    }

</script>

<!-- conteúdo do popover -->
<div id="uimap_popover" style="display: none; margin: 0; padding: 0;" data-container="body"> 
    <span id="uimap_popover_espc_name" style="font-weight: bold;">NO_NAME</span><br/>
    <span id="uimap_popover_value">VALUE</span> 
</div>
<!-- fim conteúdo do popover -->

<form id="form">
	<input id="lat" type="hidden" />
	<input id="lon" type="hidden" />
	<input type="hidden" id="zoom_extent" />
</form>



<div style="height: 650px;">
    
<table>

        <tr style="padding: 0; margin: 0; border: 0;">
            <td id="local_box2" style="padding: 0; margin: 0; border: 0;">

            </td>

            <td rowspan="3" style="padding: 0; margin: 0; border: 0; vertical-align: top;">
                
                <table>
                    
                     <tr style="padding: 0; margin: 0; border: 0; border-left: 1px solid #ccc;" >
                         <td style="padding: 0; margin: 0; border: 0;">
                             &nbsp;<span style="font-weight: bold; position: relative; top: 5px;" id="nome_do_indicador"></span>
                          </td>
                     </tr>
                   
                     <tr style="padding: 0; margin: 0; border-left: 1px solid #ccc;">
                        <td style="padding: 0; margin: 0; border: 0;">
                               <!-- canvas do mapa -->
                             <div style='position:relative; top:10px; left:0px;'>

                                   <img id="uihistogramcanvas_1" src="img/map/brasil.gif"/>

                                   <div style='position:absolute; top:0; left:0;'>
                                        <img id="uihistogramcanvas_2" src="img/map/brasil.gif"/>
                                   </div>
                                   
                                   <div style='position:absolute; top:0; left:0;'>
                                        <img id="uihistogramselection" src=""/>
                                   </div>
                                   
                                   
                                   <!-- legenda do mapa -->
                                   <button id="uimap_show_legend" data-original-title='Mostrar legenda' title data-placement='right' type="button" class="btn" onclick="show_legend_evt();" style="display: none; position: absolute; left:19px; top:567px; height: 16px; width: 16px;  padding: 2px 2px;">
                                       <img src="img/map/show-legend.png" style="height: 12px; width: 12px;" />
                                   </button>
                                   <div id="uimap_legend" style="background-color: white; position:absolute; height: 160px; top:430px; left:10px; width: 150px; background-color: white; border: 3px solid #c0c0c0;"> 
 
                                       <div style="position: relative; left: 6px; font-size: 12px; font-weight: bold;">LEGENDA</div>
                                       <img id="uilegendcanvas" src="" style="float: left;" /> 
                                       <button type="button" class="btn" onclick="close_legend_evt();" style="position: absolute; top: 135px; left: 5px; height: 16px; width: 16px;  padding: 4px 4px;">
                                          <img src="img/icons/close.gif" />
                                       </button>
                                       
                                   </div>
                                   
                                   
                                    <!-- pan buttons -->
                                    <div  style="position:absolute; top:170px; left:615px;">
                                       <button id="uipan_up"  onclick="pan_handler(new Array('up'));"   type="button" style="position:absolute; top:1px; left:20px; padding: 0; height:20px; width: 15px;" class="btn"><img src="img/map/pan/up.png" /></button>
                                       <button id="uipan_down" onclick="pan_handler(new Array('down'));" type="button" style="position:absolute; top:23px; left:20px; padding: 0;  height: 20px; width: 15px;" class="btn"><img src="img/map/pan/down.png" /></button>
                                       <button id="uipan_left" onclick="pan_handler(new Array('left'));" type="button" style="position:absolute; top:12px; left:2px; padding: 0;  height: 17px; width: 17px;" class="btn"><img src="img/map/pan/left.png" /></button>
                                       <button id="uipan_right" onclick="pan_handler(new Array('right'));" type="button" style="position:absolute; top:12px; left:36px; padding: 0;  height: 17px; width: 17px;" class="btn"><img src="img/map/pan/right.png" /></button>
                                    </div>
                                    
                                     
                                    <div class="btn-group btn-group-vertical" style="position:absolute; top:0; left:620px;">
                                        <div></div>
                                        <button id="ui_button_zoomin"  data-original-title='Mais zoom' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img height="16" width="16" src="img/map/zoom_in_gray.png" /></button>
                                        <div></div>
                                        <button id="ui_button_zoomout" data-original-title='Menos zoom' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img height="16" width="16" src="img/map/zoom_out_gray.png" /></button>
                                        <div></div>
                                        <button id="uimaptool_zoomout" data-original-title='Brasil completo' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img height="16" width="16"  src="img/map/brazil_gray.png" /></button>
                                        <div></div>
                                        <button id="uimaptool_selectregion" data-original-title='Selecionar região' title data-placement='left' type="button" class="btn" data-toggle="button" style="height:31px; width: 42px;"><img height="16" width="16" src="img/map/zoom_select_gray.png" title="Selecionar região" /></button>
                                    </div>
                                    
                                    
                                    
                                    <!-- legenda do mapa -->
                                   <button id="uimap_show_perfil" data-original-title='Exapandir miniperfil' title data-placement='left' type="button" class="btn" onclick="show_perfil_evt();" style="position: absolute; left:629px; top:567px; height: 16px; width: 16px;  padding: 1px 1px;">
                                       <img src="img/map/expand.png" style="height: 12px; width: 12px;" />
                                   </button>
                                   <div id="uimpadowninfo" style="position: absolute; top: 430px; left: 530px; background-color: white; height: 160px; width: 120px; border: 3px solid #c0c0c0;">
                                      <table style="position: absolute; left:10px; top: 10px; width: 100px; border:0px; margin:0px; padding: 0px;"> 
                                            <tbody>
                                               <tr>
                                                  <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:left; color:#808080; font-weight: bold;"> 
                                                       IDHM
                                                  </td>
                     
                                                  <td id="miniperfil_idh" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                                                     0
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                                                        <img style="height: 32px; width: 32px;" src="img/map/idh_longevidade.png" alt="Longevidade" /> 
                                                    </td>
                                         
                                                    <td id="miniperfil_longevidade" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                                                        0
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;" > 
                                                         <img style="height: 32px; width: 32px;" src="img/map/idh_renda.png" alt="Renda" /> 
                                                    </td>
                                        
                                                    <td id="miniperfil_renda" style="border:0; height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                                                        0
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                                                         <img style="height: 32px; width: 32px;" src="img/map/idh_educacao.png" alt="Educação"/> 
                                                    </td>
                                           
                                                    <td id="miniperfil_educacao" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">
                                                        0
                                                    </td>
                                                </tr>
                                                </tbody>  
                                        </table>
                                        
  
                                        <a id="uihistogram_popover_perfil_link" style="display: none; position: absolute; width: 150px; top: 135px; left: 14px;" target="_blank" href="javascript:void(0);" class="uimap_popover_link">Exibir perfil</a>
                                 
                                        <button id="uimap_buton_popover_close_button" type="button" class="btn" onclick="close_popover_evt();" style="position: absolute; left: 95px; top: 135px; height: 16px; width: 16px;  padding: 4px 4px;">
                                          <img src="img/icons/close.gif" />
                                        </button>
     
                                   </div>
                                    
                                   <div>
                                      <img id="uihistogrampixel" style="position: absolute; top: 0; left: 0; background-color: transparent;" src="img/map/map-pin.png" />
                                   </div>
                                  
                                   <img id="uihistogramloader" src="img/map/ajax-loader.gif" style="position: absolute; top: 300px; left: 300px; background-color: transparent;" />
                             </div>
                        </td>
                     </tr>
        
                 </table>

            </td>
         </tr>

         <tr>
             <td>
                <div id="box_indicador_local2"> </div>
            </td>
            <td></td>
         </tr>
         <tr style="margin:0px; padding: 0px; border: 0px;" >
             <td  style="margin:0px; padding: 0px; border: 0px;">
                 <span style="font-weight: bold; display:block; margin-left:24px; width:44px">ANOS</span>
                  <div>
                     <div class='labels'>
                       <span class="one">1991</span>
                       <span class="two">2000</span>
                       <span class="tree">2010</span>
                     </div>
                  </div>
                  <div class="sliderDivFather">
                    <div class="sliderDivIn">
                         <input type='text' id="histogram_year_slider" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" />
                    </div>    
                  </div>
             </td>
             <td  style="margin:0px; padding: 0px; border: 0px;" ></td>
         </tr>

</table>

   
</div>

