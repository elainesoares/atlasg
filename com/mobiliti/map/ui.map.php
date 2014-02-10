<script type="text/javascript">


    /*****************************
     script: ui.map
     author: Reinaldo Aparecido Rocha Filho
     *****************************/
    var map_e = null;
    var map_l = null;
    var map_i = null;
    var map_i_name = "";
    var map_a = 3;
    var map_s = null;
    
    var skip_click = true;

    var quantil_id = "";

    var map_legend_button_is_lock = false;

    var map_width = 651;
    var map_height = 619;


    var MAP_TOOL_INFO = 1;
    var MAP_TOOL_SELC = 2;
    var map_tool = MAP_TOOL_INFO;

    var map_actual_canvas = 2;
    var map_use_interpolation = false;

    var map_extent = "-77.12 -38.98 -29.15 8.99";
    var map_max_extent = "-77.12 -38.98 -29.15 8.99";

    var local;
    var map_indc;
    var map_indc_selector;

    var ___first_time_year_ = true;

    var zoom_step = 3;
    var PAN_STEP = 2;

    $(document).ready(function()
    {
        $('#local_box').load('com/mobiliti/componentes/local/local.html', function()
        {
            local = new SeletorLocal();
            local.startLocal(listenerLocalMapa, "local_box", false);
            local.setListenerSelectionItem(map_destacar_regiao);

            map_indc = new LocalSelector();
            local.setButton(map_indc.html('uimaplocal_selector'))
            map_indc.startSelector(true, "uimaplocal_selector", map_indcator_selector_mapa, "right", "mapEditIndicador", true);
        });

        $('#box_indicador_local').load('com/mobiliti/componentes/local_indicador/indicador.html', function()
        {
            indicadorLocal = new SeletorIndicador();
            indicadorLocal.startLocal(listenerLocalIndicadores, "box_indicador_local", false);
            try {
                map_indc_selector = new IndicatorSelector();
                indicadorLocal.setButton(map_indc_selector.html('mapEditIndicador'));

                map_indc_selector.startSelector(true, "mapEditIndicador", seletor_indicador, "right", false, "uimaplocal_selector", true);

            } catch (e) {
                //erro
            }
        });


        map_init();
    });

    function listenerLocalIndicadores(indicadores)
    {
        geral.setIndicadores(indicadores);
        map_indc_selector.refresh();
    }

    function seletor_indicador(obj)
    {
        geral.setIndicadores(obj);
        indicadorLocal.refresh();
    }

    function map_indcator_selector_mapa(array)
    {
        local.setItensSelecionados(array);
    }

    function listenerLocalMapa(lugares)
    {
        geral.setLugares(lugares);
    }

    function map_init()
    {

        $('.nav-tabs').button();

        $("#map_year_slider").bind("slider:changed", map_year_slider_listener);


        /*configura o popover sobre o mapa*/
        $('#uimappixel').popover({
            trigger: 'manual',
            html: true,
            template: '<div class="popover" style="width:150px"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><div></div></div></div></div>',
            animation: true,
            delay: {show: 1500, hide: 1500},
            placement: get_map_popover_placement,
            content: function() {
                return $('#uimap_popover').html();
            }
        });

        $('#uimappixel').popover('hide');
        $('#uimappixel').hide();
        $("#uimapselection").hide();

        $("#uimapselection").css("min-height", map_height + "px");
        $("#uimapselection").css("min-width", map_width + "px");

        $("#uimapcanvas_1").css("min-height", map_height + "px");
        $("#uimapcanvas_1").css("min-width", map_width + "px");

        $("#uimapcanvas_2").css("min-height", map_height + "px");
        $("#uimapcanvas_2").css("min-width", map_width + "px");

        $("#uimaptool_selectregion").click(uimaptool_selectregion_event);
        $("#uimaptool_zoomout").click(uimaptool_zoomout_event);


        map_loading(false);

        //-----------------------------------
        $("#uimaptool_info").addClass('active');
        //---------------------------------------
        // Botões in e out do zoom
        // --------------------------------------
        $("#ui_button_zoomin").click(ui_button_zoomin_click_evt);
        $("#ui_button_zoomout").click(ui_button_zoomout_click_evt);
        //---------------------------------------


        //mousedown sobre o mapa
        $("#uimapcanvas_1").mousedown(uimap_mouse_down_handler);
        $("#uimapcanvas_2").mousedown(uimap_mouse_down_handler);
        $("#uimapselection").mousedown(uimap_mouse_down_handler);

        //mouseup sobre o mapa
        $("#uimapcanvas_1").mouseup(uimap_mouse_up_handler);
        $("#uimapcanvas_2").mouseup(uimap_mouse_up_handler);
        $("#uimapselection").mouseup(uimap_mouse_up_handler);
        //mousemove sobre o mapa
        $("#uimapcanvas_1").mousemove(uimap_mouse_move_handler);
        $("#uimapcanvas_2").mousemove(uimap_mouse_move_handler);
        $("#uimapselection").mousemove(uimap_mouse_move_handler);
        //mousemove sobre o mapa
        $("#uimapcanvas_1").mouseout(uimap_mouse_out_handler);
        $("#uimapcanvas_2").mouseout(uimap_mouse_out_handler);
        $("#uimapselection").mouseout(uimap_mouse_out_handler);
        //Borda arredondada 
        $("#uimpadowninfo").css("-webkit-border-radius", "10px");
        $("#uimpadowninfo").css("-moz-border-radius", "10px");
        $("#uimpadowninfo").css("border-radius", "10px");


        //configura os tooltips
        $("#ui_button_zoomin").tooltip({delay: 500});
        $("#ui_button_zoomout").tooltip({delay: 500});
        $("#uimaptool_zoomout").tooltip({delay: 500});
        $("#uimaptool_selectregion").tooltip({delay: 500});
        $("#uimap_show_legend").tooltip({delay: 500});
        $("#uimap_show_perfil").tooltip({delay: 500});
        $("#link_quintil").tooltip({delay: 500});
        $("#link_quintil_voltar").tooltip({delay: 500});



        $("#uimap_legend").css("-webkit-border-radius", "7px");
        $("#uimap_legend").css("-moz-border-radius", "7px");
        $("#uimap_legend").css("border-radius", "7px");

        $('#uimapcanvas_1').load(canvas_load_handler);
        $('#uimapcanvas_2').load(canvas_load_handler);
        $('#uimapselection').load(uimaptool_selectregion_load_handler);

    }


    var uimap_is_down = false;
    var uimap_pos_x = 0;
    var uimap_pos_y = 0;
    function uimap_mouse_down_handler(e)
    {
        e.preventDefault();
        uimap_is_down = true;

        var offset = $(this).offset();
        uimap_pos_x = (e.pageX - offset.left);
        uimap_pos_y = (e.pageY - offset.top);

        return 0;
    }

    function uimap_mouse_move_handler(e)
    {
        e.preventDefault();

        if (uimap_is_down && map_tool === MAP_TOOL_INFO)
        {
            $('#uimapcanvas_1').css("cursor", "move");
            $('#uimapcanvas_2').css("cursor", "move");
            $('#uimapselection').css("cursor", "move");

        }
        else if (map_tool === MAP_TOOL_SELC)
        {
            $('#uimapcanvas_1').css("cursor", "crosshair");
            $('#uimapcanvas_2').css("cursor", "crosshair");
            $('#uimapselection').css("cursor", "crosshair");
        }
        else if (uimap_is_down && map_tool === MAP_TOOL_SELC)
        {
            $('#uimapcanvas_1').css("cursor", "crosshair");
            $('#uimapcanvas_2').css("cursor", "crosshair");
            $('#uimapselection').css("cursor", "crosshair");
        }
        else
        {
            $('#uimapcanvas_1').css("cursor", "default");
            $('#uimapcanvas_2').css("cursor", "default");
            $('#uimapselection').css("cursor", "default");
        }

        return 0;
    }

    function uimap_mouse_up_handler(e)
    {
        e.preventDefault();

        if (!uimap_is_down)
            return 0;
        else
            uimap_is_down = false;

        var offset = $(this).offset();
        var posy = (e.pageY - offset.top);
        var posx = (e.pageX - offset.left);

        if (posx === uimap_pos_x && posy === uimap_pos_y)
        {
            map_click_evt(e, this);
        }
        else
        {
            var msg = "Handler for .mouseup() diff at ";

            var diff_x = posx - uimap_pos_x;
            var diff_y = posy - uimap_pos_y;

            msg += diff_x + ", " + diff_y;

            var directions = new Array();

            //Se x > 0 ir para direita, senão, ir para esquerda
            if (Math.abs(diff_x) > 50)
            {
                if (diff_x > 0)
                    directions.push('left');
                else
                    directions.push('right');
            }

            //Se y > 0 ir para cima, senão, ir para baixo
            if (Math.abs(diff_y) > 50)
            {
                if (diff_y > 0)
                    directions.push('up');
                else
                    directions.push('down');
            }

            if (directions.length > 0)
                pan_handler(directions);
        }
        return 0;
    }

    function uimap_mouse_out_handler(e)
    {
        e.preventDefault();
        uimap_is_down = false;
    }

    function map_click_evt(e, map)
    {
      
        if(skip_click && map_e != 4)return;
        
        e.preventDefault();
        var offset = $(map).offset();

        var px_lat = (e.pageY - offset.top);
        var px_lon = (e.pageX - offset.left);

        if (map_tool == MAP_TOOL_INFO)
        {
            $("#uimappixel").show();
            $("#uimappixel").attr("src", "<?php echo $path_dir ?>img/map/ajax-pin-loader.gif");

            map_positionate_pin(px_lat, px_lon);
            map_build_popover_content(px_lat, px_lon);
        }
        return 0;
    }


    function ui_button_zoomin_click_evt(e)
    {
        map_hide_selection();

        var coo = map_extent.split(" ");

        var minx = parseFloat(coo[0]);
        var miny = parseFloat(coo[1]);
        var maxx = parseFloat(coo[2]);
        var maxy = parseFloat(coo[3]);

        minx = minx + zoom_step;
        miny = miny + zoom_step;
        maxx = maxx - zoom_step;
        maxy = maxy - zoom_step;

        var diff = maxx - minx;

        if (diff <= 0)
        {
            var minx = parseFloat(coo[0]);
            var miny = parseFloat(coo[1]);
            var maxx = parseFloat(coo[2]);
            var maxy = parseFloat(coo[3]);

            minx = minx + 0.2;
            miny = miny + 0.2;
            maxx = maxx - 0.2;
            maxy = maxy - 0.2;

            diff = maxx - minx;
        }

        if (diff <= 0)
            return;

        coo[0] = minx;
        coo[1] = miny;
        coo[2] = maxx;
        coo[3] = maxy;

        map_extent = coo.join(" ");
        map_load(map_e, map_l, map_i, map_a, false, true);
    }

    function ui_button_zoomout_click_evt(e)
    {
        map_hide_selection();

        var coo = map_extent.split(" ");

        var minx = parseFloat(coo[0]);
        var miny = parseFloat(coo[1]);
        var maxx = parseFloat(coo[2]);
        var maxy = parseFloat(coo[3]);

        //superior esquerdo

        minx = minx - zoom_step;
        miny = miny - zoom_step;
        maxx = maxx + zoom_step;
        maxy = maxy + zoom_step;

        coo[0] = minx;
        coo[1] = miny;
        coo[2] = maxx;
        coo[3] = maxy;

        map_extent = coo.join(" ");
        map_load(map_e, map_l, map_i, map_a, false, true);
    }


    function uimaptool_zoomout_event(event)
    {
        map_hide_selection();

        map_extent = map_max_extent;
        map_load(map_e, map_l, map_i, map_a, false, true);
    }

    function map_indcator_selector_listener(obj) {
    }
    function map_change_list_indicator(arr) {
    }


    function map_year_slider_listener(event, data)
    {
        if (___first_time_year_)
        {
            ___first_time_year_ = false;
            return;
        }

        map_hide_selection();

        if (data.value === 1991)
            map_a = 1;
        else if (data.value === 2000)
            map_a = 2;
        else if (data.value === 2010)
            map_a = 3;

        map_use_interpolation = true;

        //-----------------------------------
        // Muda todos os anos dos indicadores
        //-----------------------------------
        var _indicadores = geral.getIndicadores();
        for (var i = 0; i < _indicadores.length; i++)
        {
            geral.updateIndicador(i, map_a);
        }
        // ----------------------------------

        map_load(map_e, map_l, map_i, map_a, false, true);
    }


    function map_hide_selection()
    {
        $("#p_selection").val("");
        $('#uimappixel').popover('hide');
        $('#uimappixel').hide();
        $("#uimapselection").hide();

        $("#miniperfil_idh").html("-");
        $("#miniperfil_longevidade").html("-");
        $("#miniperfil_renda").html("-");
        $("#miniperfil_educacao").html("-");
        $("#uimap_popover_perfil_link").hide();

        return 0;
    }


    function uimaptool_selectregion_event(event)
    {
        map_hide_selection();

        if (map_tool === MAP_TOOL_INFO)
        {
            map_tool = MAP_TOOL_SELC;

            $('#uimapcanvas_1').imgAreaSelect({autoHide: true, handles: true, hide: false, disable: false, onSelectEnd: map_selection_event});
            $('#uimapcanvas_2').imgAreaSelect({autoHide: true, handles: true, hide: false, disable: false, onSelectEnd: map_selection_event});
            $('#uimapselection').imgAreaSelect({autoHide: true, handles: true, hide: false, disable: false, onSelectEnd: map_selection_event});

        }
        else
        {
            map_tool = MAP_TOOL_INFO;

            $('#uimapcanvas_1').imgAreaSelect({hide: true, disable: true});
            $('#uimapcanvas_2').imgAreaSelect({hide: true, disable: true});
        }
    }

    function map_selection_event(img, selection)
    {

        if (!selection.width || !selection.height)
            return;

        $('#zoom_extent').val(selection.x1 + " " + selection.y1 + " " + selection.x2 + " " + selection.y2);
        map_load(map_e, map_l, map_i, map_a, true, true);

        return 0;

    }

    function get_map_popover_placement(pop, dom_el)
    {

        var px_left = $('#uimappixel').position().left;
        var px_top = $('#uimappixel').position().top;


        if (px_top > 500 && px_left > 300)
            return 'left';

        if (px_top > 500)
            return 'top';

        if (px_left > 250)
            return 'left';

        return 'right';
    }


    function map_loading(status)
    {
        if (status)
            $("#uimaploader").show();
        else
            $("#uimaploader").hide();
    }


    function map_response(data, textStatus, jqXHR)
    {
        map_hide_selection();
        
        //desativa o botão de seleção de região
        map_tool = MAP_TOOL_INFO;
        $("#uimaptool_selectregion").removeClass("active");
        $('#uimapcanvas_1').imgAreaSelect({hide: true, disable: true});
        $('#uimapcanvas_2').imgAreaSelect({hide: true, disable: true});
        //------------------------

        if (textStatus === "success")
        {

            var ano_result_to_fill = '';

            if (map_a === 1)
                ano_result_to_fill = "1991";
            else if (map_a === 2)
                ano_result_to_fill = "2000";
            else if (map_a === 3)
                ano_result_to_fill = "2010";

            $("#map_year_slider").simpleSlider("setValue", ano_result_to_fill);
            
            if(map_i_name == "")
                $("#nome_do_indicador").html("");
            else
                $("#nome_do_indicador").html(ano_result_to_fill + " - " + map_i_name);
            
            
            $("#p_indicador").val(map_i_name);
            $("#p_ano").val(ano_result_to_fill);


            var obj = $.parseJSON(data);
            map_extent = obj.extentToHTML;

            if (map_actual_canvas == 1)
                $("#uimapcanvas_2").attr("src", obj.imageURL);
            else if (map_actual_canvas == 2)
                $("#uimapcanvas_1").attr("src", obj.imageURL);

            $("#p_map").val(obj.imageURL);
            $("#p_legend").val(obj.legendURL);
            $("#uilegendcanvas").attr("src", obj.legendURL);

            quantil_id = obj.quantilID;

            if (obj.panStep)
                PAN_STEP = obj.panStep;
            
            skip_click  = obj.skip_click;
        }
    }


    function map_load(e, l, i, a, zoom, istool)
    {


        map_loading(true);

        var map_data = new Object();

        //define os ids
        map_data['e'] = e; // espacialidade
        if (l.length)
            map_data['l'] = l.toString(); // array de locais em modo texto     
        else
            map_data['l'] = new Array(0);
        map_data['i'] = i; // indicador
        map_data['a'] = a; // ano

        map_e = e;
        map_l = l;
        map_i = i;
        map_a = a;



        map_data['height'] = map_height;
        map_data['width'] = map_width;
        map_data['extent'] = map_extent;
        map_data['istool'] = istool;

        map_data['quantil_id'] = quantil_id;


        if (zoom)
        {
            map_data['zoom_extent'] = $("#zoom_extent").val();
        }
        else {
            map_data['zoom_extent'] = null;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/map/map.controller.php",
            data: map_data,
            success: map_response
        });


    }



    function map_positionate_pin(px_lat, px_lon)
    {
        $("#lat").val(px_lat);
        $("#lon").val(px_lon);

        $("#uimappixel").css("left", (px_lon - 7));
        $("#uimappixel").css("top", (px_lat - 10));

        return 0;
    }

    function map_build_popover_content(px_lat, px_lon)
    {

        var result = $.parseJSON('{"title":"", "arvore":true, "grafico":true, "perfil":false}');

        $("#uimap_popover_idh_tree").hide();
        $("#uimap_popover_chart").hide();


        $("#uimap_popover_perfil_link").hide();

        map_popover_request(px_lat, px_lon);
        return 0;
    }


    function map_update_popover(popdata)
    {
        if (popdata.id)
        {

            if (popdata.estado)
            {
                $("#uimap_popover_espc_name").html(popdata.title + " (" + popdata.estado + ")");
                $("#p_nome_local").val(popdata.title + " (" + popdata.estado + ")");
                $("#p_title").val("MUNICÍPIO");
            }
            else
            {
                $("#uimap_popover_espc_name").html(popdata.title);
                $("#p_nome_local").val(popdata.title);
                $("#p_title").val("ESTADO");
            }
            
            
            
            if(popdata.value == -1)
            {
                 $("#uimap_popover_value").html("");   
            }
            else
            {
               $("#uimap_popover_value").html(addPontoDeMilhar((parseFloat(popdata.value).toFixed(popdata.decimais) + "").replace(".", ","))); 
            }
   

            if (popdata.value != null)
                $("#uimap_popover_value").show();
            else
                $("#uimap_popover_value").hide();


            //------------------------------------
            //      Informações do miniperfil
            //------------------------------------

            var value_idh = "";
            if (!(popdata.idh === null || popdata.idh === undefined))
                value_idh = parseFloat(popdata.idh).toFixed(3);
            
            if(value_idh == -1)
            {    
                $("#miniperfil_idh").html("-");
                $("#p_value_idh").val("-");
            }
            else
            {
                $("#miniperfil_idh").html((value_idh + "").replace(".", ","));
                $("#p_value_idh").val((value_idh + "").replace(".", ","));
            }

            var value_longevidade = "";
            if (!(popdata.longevidade === null || popdata.longevidade === undefined))
                value_longevidade = parseFloat(popdata.longevidade).toFixed(3);
            
            if(value_longevidade == -1)
            {
                
                $("#miniperfil_longevidade").html("-");
                $("#p_value_longevidade").val("-");
            }
            else
            {
                $("#miniperfil_longevidade").html((value_longevidade + "").replace(".", ","));
                $("#p_value_longevidade").val((value_longevidade + "").replace(".", ","));
            }

            var value_renda = "";
            if (!(popdata.renda === null || popdata.renda === undefined))
                value_renda = parseFloat(popdata.renda).toFixed(3);
            
            if(value_renda == -1)
            {
                    
                $("#miniperfil_renda").html("-");
                $("#p_value_renda").val("-");
            }
            else
            {
                $("#miniperfil_renda").html((value_renda + "").replace(".", ","));
                $("#p_value_renda").val((value_renda + "").replace(".", ","));
            }

            var value_educacao = "";
            if (!(popdata.educacao === null || popdata.educacao === undefined))
                value_educacao = parseFloat(popdata.educacao).toFixed(3);
            
            if(value_educacao == -1)
            {
                $("#miniperfil_educacao").html("-");
                $("#p_value_educacao").val("-");
            }
            else
            {
                
                $("#miniperfil_educacao").html((value_educacao + "").replace(".", ","));
                $("#p_value_educacao").val((value_educacao + "").replace(".", ","));
            }

            //------------------------------------
            // Seta o destino para o perfil
            //------------------------------------
            if (popdata.estado)
            {
                $("#uimap_popover_perfil_link").attr("href", "perfil/" + popdata.perfil);
                $("#uimap_popover_perfil_link").show();
            }
            //------------------------------------


            $("#uimappixel").attr("src", "<?php echo $path_dir ?>img/map/map-pin.png");


            map_positionate_pin(popdata.px_lat, popdata.px_lon);
            $('#uimappixel').popover('show');

            if (popdata.selection)
            {
                $("#uimapselection").attr("src", popdata.selection);
                $("#p_selection").val(popdata.selection);
            }
        }
        else
        {
            map_hide_selection();
        }

        return 0;
    }
    
    //não usar por enquanto
    function convert_to_pixel(latitude, longitude)
    {

        // get x value
        var x = (map_width*(180+longitude)/360)%map_width+(map_width/2);

        // convert from degrees to radians
        var latRad = latitude*Math.PI/180;

        // get y value
        var mercN = Math.log(Math.tan((Math.PI/4)+(latRad/2)));
        var y     = (map_height/2)-(map_width*mercN/(2*Math));
        
        return new Array(x,y);
    }

    function map_popover_request(_px_lat, _px_lon)
    {
        
        var request_data = $.parseJSON('{"spac":"", "px_lat":0, "px_lon":0, "extent":"", "height":0, "width":0, "selection":true, "indc":"" , "year":"" }');

        request_data.spac = map_e;
        request_data.px_lat = _px_lat;
        request_data.px_lon = _px_lon;
        request_data.extent = map_extent;
        request_data.height = map_height;
        request_data.width = map_width;
        request_data.indc = map_i;
        request_data.year = map_a;


        $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/map/map.spatialquery.service.php",
            data: request_data,
            success: map_popover_response
        });

        return 0;
    }


    function map_popover_response(data, textStatus, jqXHR)
    {
        
        if (textStatus === "success")
        {
            var obj = $.parseJSON(data);
            map_update_popover(obj);
            local.selectedElement(obj.id);
        }

        return 0;
    }



    function map_listener_indicador(event, obj)
    {
        quantil_id = "";

        if (event === "changetab")
        {
            geral.removeIndicadoresDuplicados();
        }

        map_indc_selector.refresh();
        indicadorLocal.refresh();

        //-----------------------------------
        // Muda todos os anos dos indicadores
        //-----------------------------------
        var _indicadores = geral.getIndicadores();
        for (var i = 0; i < _indicadores.length; i++)
        {
            geral.updateIndicador(i, map_a);
        }

        // AO Mudar de aba disparar o dispacth_map_evt somente uma vez!
        if (event !== "changetab")
            dispacth_map_evt(true);
    }


    function map_listener_lugar(event, obj)
    {

        quantil_id = "";
        local.refresh();
        map_indc.refresh();
        dispacth_map_evt();
    }

    function dispacth_map_evt()
    {
        // limpa todos os argumentos
        map_i_name = "";
        map_i = 0;
        map_e = 0;
        map_l = new Array();

        //limpa a seleção
        map_hide_selection();
        $("#link_quintil").css("font-weight", "normal");
        $("#link_quintil_voltar").hide();

        //obtem os locais e espacialidade
        var _locais = geral.getLugaresPorEspacialidadeAtiva();

        if (!(_locais === undefined || _locais === null || _locais === ""))
        {
            map_e = _locais.e;
            //locais a serem exibidos no mapa ou destacadas
            for (var i = 0; i < _locais.l.length; i++)
            {
                var _lugar = _locais.l[i];
                if (_lugar)
                {
                    if (_lugar.c)
                        map_l.push(_lugar.id);
                }
            }
        }

        //obtem o indicador
        var _indicadores = geral.getIndicadores();
        if (!(_indicadores === undefined || _indicadores === null || _indicadores === ""))
        {
            for (var i = 0; i < _indicadores.length; i++)
            {
                var _indicador = _indicadores[i];

                if (_indicador.c)
                {
                    map_i = _indicador.id;
                    map_a = _indicador.a;

                    //nome completo
                    map_i_name = _indicador.desc;
                    break;
                }
            }
        }

        map_load(map_e, map_l, map_i, map_a, false, false);
    }

    function map_destacar_regiao(id, e)
    {
        map_hide_selection();

        if (e == 7)
            return;

        map_loading(true);
        var request_data = new Object();
        request_data.spac = e;
        request_data.local = id;
        request_data.extent = map_extent;
        request_data.height = map_height;
        request_data.width = map_width;
        request_data.indc = map_i;
        request_data.year = map_a;

        $.ajax({
            type: "POST",
            url: "<?php echo $path_dir ?>com/mobiliti/map/map.spatialquery.php",
            data: request_data,
            success: map_popover_response
        });
    }


    function pan_handler(drcts)
    {
        map_hide_selection();

        var coo = map_extent.split(" ");

        var minx = parseFloat(coo[0]);
        var miny = parseFloat(coo[1]);
        var maxx = parseFloat(coo[2]);
        var maxy = parseFloat(coo[3]);


        for (var i = 0; i < drcts.length; i++)
        {
            if (drcts[i] === 'up')
            {
                miny = miny + PAN_STEP;
                maxy = maxy + PAN_STEP;
            }
            else if (drcts[i] === 'down')
            {
                miny = miny - PAN_STEP;
                maxy = maxy - PAN_STEP;
            }
            if (drcts[i] === 'left')
            {
                minx = minx - PAN_STEP;
                maxx = maxx - PAN_STEP;
            }
            else if (drcts[i] === 'right')
            {
                minx = minx + PAN_STEP;
                maxx = maxx + PAN_STEP;
            }

            coo[0] = minx;
            coo[1] = miny;
            coo[2] = maxx;
            coo[3] = maxy;
        }

        map_extent = coo.join(" ");
        map_load(map_e, map_l, map_i, map_a, false, true);
    }


    function close_legend_evt()
    {
        $("#uimap_legend").hide();
        $("#uimap_show_legend").show();
    }

    function show_legend_evt()
    {
        $("#uimap_legend").show();
        $("#uimap_show_legend").hide();
    }

    function show_perfil_evt()
    {
        $("#uimap_show_perfil").hide();
        $("#uimpadowninfo").show();
    }

    function close_popover_evt()
    {
        $("#uimpadowninfo").hide();
        $("#uimap_show_perfil").show();
    }


    function canvas_load_handler()
    {
        var id_image = $(this).attr('id')
        map_loading(false);

        var duration = 50;
        if (map_use_interpolation)
            duration = 1000;
        map_use_interpolation = false;


        if (id_image == "uimapcanvas_1")
        {
            $("#uimapcanvas_1").fadeTo(duration, 1);
            $("#uimapcanvas_2").fadeTo(duration, 0);
            map_actual_canvas = 1;
        }
        else if (id_image == "uimapcanvas_2")
        {
            $("#uimapcanvas_1").fadeTo(duration, 0);
            $("#uimapcanvas_2").fadeTo(duration, 1);
            map_actual_canvas = 2;
        }

        return 0;
    }

    function uimaptool_selectregion_load_handler()
    {
        map_loading(false);
        $("#uimapselection").show();
        return 0;
    }

    function make_quintil()
    {
        quantil_id = "make";

        $("#link_quintil").css("font-weight", "bold");
        $("#link_quintil_voltar").show();

        map_load(map_e, map_l, map_i, map_a, false, true);
        return 0;
    }

    function make_normal()
    {
        quantil_id = "";

        $("#link_quintil").css("font-weight", "normal");
        $("#link_quintil_voltar").hide();

        map_load(map_e, map_l, map_i, map_a, false, true);
        return 0;
    }
    
    function addPontoDeMilhar(nStr)
    {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
    }

</script>

<!-- conteúdo do popover -->
<div id="uimap_popover" style="display: none; margin: 0; padding: 0;" data-container="body"> 
    <span id="uimap_popover_espc_name" style="font-weight: bold;">NO_NAME</span><br/>
    <span id="uimap_popover_value">VALUE</span> 
</div>
<!-- fim conteúdo do popover -->

<!--<form id="form">
    <input id="lat" type="hidden" />
    <input id="lon" type="hidden" />
    <input type="hidden" id="zoom_extent" />
</form>
-->


<div style="height: 664px;">

    <table>

        <tr style="padding: 0; margin: 0; border: 0;">
            <td id="local_box" style="padding: 0; margin: 0; border: 0;">

            </td>

            <td rowspan="3" style="padding: 0; margin: 0; border: 0; vertical-align: top;">

                <table>

                    <tr style="padding: 0; margin: 0; border: 0; border-left: 1px solid #ccc;" >
                        <td style="padding: 0; margin: 0; border: 0;">
                            <div style="width: 650px; font-weight: bold; position: relative; top: 4px; font-size: 12pt; height: 35px; padding: 0; margin: 0; border: 0; vertical-align: middle;">
                                <center id="nome_do_indicador">

                                </center>
                            </div>
                        </td>
                    </tr>

                    <tr style="padding: 0; margin: 0; border-left: 1px solid #ccc;">
                        <td style="padding: 0; margin: 0; border: 0;">
                            <!-- canvas do mapa -->
                            <div style='position:relative; top:10px; left:0px;'>

                                <img id="uimapcanvas_1" src="img/map/brasil.gif"/>

                                <div style='position:absolute; top:0; left:0;'>
                                    <img id="uimapcanvas_2" src="img/map/brasil.gif"/>
                                </div>

                                <div style='position:absolute; top:0; left:0;'>
                                    <img id="uimapselection" src=""/>
                                </div>

                                <!-- legenda do mapa -->
                                <button id="uimap_show_legend" data-original-title='Mostrar legenda' title data-placement='right' type="button" class="btn" onclick="show_legend_evt();" style="display: none; position: absolute; left:3px; top:580px; height:31px; width: 42px;  padding: 2px 2px;">
                                    <img src="img/map/show-legend.png" style="height: 16px; width: 16px;" />
                                </button>
                                <div id="uimap_legend" style="background-color: white; position:absolute; height: 155px; top:455px; left:3px; width: 130px; background-color: white; border: 3px solid #c0c0c0;"> 

                                    <a id="link_quintil_voltar" data-original-title='Retornar a exibição padrão.' title data-placement='top'  style="display: none;" href="javascript:function(){return 0};" onclick="make_normal();">Voltar</a>&nbsp;
                                    <a id="link_quintil"  data-original-title='Gerar quintil a partir da seleção atual.' title data-placement='top' href="javascript:function(){return 0};" onclick="make_quintil();">Quintil</a>

                                    <div style="position: relative; left: 6px; font-size: 12px; font-weight: bold;">LEGENDA</div>
                                    <img id="uilegendcanvas" src="" style="float: left;" /> 
                                    <button type="button" class="btn" onclick="close_legend_evt();" style="position: absolute; top: 4px; left: 105px; height: 16px; width: 16px;  padding: 0px 0px;">
                                        <img src="img/map/btn_minimizar.png" />
                                    </button>
                                </div>


                                <!-- pan buttons -->
                                <div  style="position:absolute; top:140px; left:587px;">
                                    <div id="uipan_up"  onclick="pan_handler(new Array('up'));"   type="button" style="position:absolute; top:1px; left:25px; padding: 0; height:20px; width: 17px;" class="btn"><img src="img/map/pan/up.png" /></div>
                                    <div id="uipan_down" onclick="pan_handler(new Array('down'));" type="button" style="position:absolute; top:25px; left:25px; padding: 0;  height: 20px; width: 17px;" class="btn"><img src="img/map/pan/down.png" /></div>
                                    <div id="uipan_left" onclick="pan_handler(new Array('left'));" type="button" style="position:absolute; top:12px; left:5px; padding: 0;  height: 20px; width: 17px;" class="btn"><img src="img/map/pan/left.png" /></div>
                                    <div id="uipan_right" onclick="pan_handler(new Array('right'));" type="button" style="position:absolute; top:12px; left:45px; padding: 0;  height: 20px; width: 17px;" class="btn"><img src="img/map/pan/right.png" /></div>
                                </div>


                                <div class="btn-group btn-group-vertical" style="position:absolute; top:4px; left:600px;">
                                    <div></div>
                                    <button id="ui_button_zoomin"  data-original-title='Mais zoom' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img style="height: 16px; width: 16px" src="img/map/zoom_in_gray.png" /></button>
                                    <div></div>
                                    <button id="ui_button_zoomout" data-original-title='Menos zoom' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img style="height: 16px; width: 16px" src="img/map/zoom_out_gray.png" /></button>
                                    <div></div>
                                    <button id="uimaptool_zoomout" data-original-title='Brasil completo' title data-placement='left' type="button" style="height:31px; width: 42px;" class="btn"><img style="height: 16px; width: 16px"  src="img/map/brazil_gray.png" /></button>
                                    <div></div>
                                    <button id="uimaptool_selectregion" data-original-title='Selecionar região' title data-placement='left' type="button" class="btn" data-toggle="button" style="height:31px; width: 42px;"><img style="height: 16px; width: 16px" src="img/map/zoom_select_gray.png" title="Selecionar região" /></button>
                                </div>



                                <!-- legenda do mapa -->
                                <button id="uimap_show_perfil" data-original-title='Mostrar miniperfil' title data-placement='left' type="button" class="btn" onclick="show_perfil_evt();" style="display: none; position: absolute; left:600px; top:580px; height:31px; width: 42px;   padding: 1px 1px;">
                                    <img src="img/map/expand.png" style="height: 16px; width: 16px;" />
                                </button>
                                <div id="uimpadowninfo" style="cursor: default; position: absolute; top: 455px; left: 512px; background-color: white; height: 155px; width: 130px; border: 3px solid #c0c0c0;">
                                    <br/>
                                    <table style="position: absolute; left:10px; top: 10px; width: 100px; border:0px; margin:0px; padding: 0px;"> 
                                        <tbody>
                                            <tr>
                                                <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:left; color:#808080; font-weight: bold;"> 
                                                    <img style="height: 32px; width: 32px;" src="img/map/idhm.png" alt="Longevidade" /> 
                                                </td>

                                                <td id="miniperfil_idh" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                                                    <img style="height: 32px; width: 32px;" src="img/map/idh_longevidade.png" alt="Longevidade" /> 
                                                </td>

                                                <td id="miniperfil_longevidade" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;" > 
                                                    <img style="height: 32px; width: 32px;" src="img/map/idh_renda.png" alt="Renda" /> 
                                                </td>

                                                <td id="miniperfil_renda" style="border:0; height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px;"> 
                                                    <img style="height: 32px; width: 32px;" src="img/map/idh_educacao.png" alt="Educação"/> 
                                                </td>

                                                <td id="miniperfil_educacao" style="height: 32px; width: 32px; border:0px; margin:0px; padding: 0px; vertical-align: middle; text-align:center;">

                                                </td>
                                            </tr>
                                        </tbody>  
                                    </table>


                                    <a id="uimap_popover_perfil_link" style="display: none; position: absolute; width: 150px; top: 135px; left: 14px;" target="_blank" href="javascript:void(0);" class="uimap_popover_link">Exibir perfil</a>

                                    <button id="uimap_buton_popover_close_button" type="button" class="btn" onclick="close_popover_evt();" style="position: absolute; left: 105px; top: 5px; height: 16px; width: 16px;  padding: 0px 0px;">
                                        <img src="img/map/btn_minimizar.png" />
                                    </button>

                                </div>

                                <div>
                                    <img id="uimappixel" style="position: absolute; top: 0; left: 0; background-color: transparent;" src="img/map/map-pin.png" />
                                </div>

                                <img id="uimaploader" src="img/map/ajax-loader.gif" style="position: absolute; top: 300px; left: 300px; background-color: transparent;" />
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>

        <tr>
            <td>
                <div id="box_indicador_local"> </div>
            </td>
            <td></td>
        </tr>
        <tr style="margin:0px; padding: 0px; border: 0px;" >
            <td  style="margin:0px; padding: 0px; border: 0px; border-right: 1px solid #ccc;">
                <span style="font-weight: bold; display:block; margin-left:24px; width:44px">ANO</span>
                <div>
                    <div class='labels'>
                        <span class="one">1991</span>
                        <span class="two">2000</span>
                        <span class="tree">2010</span>
                    </div>
                </div>
                <div class="sliderDivFather">
                    <div class="sliderDivIn">
                        <input type='text' id="map_year_slider" data-slider="true" data-slider-values="1991,2000,2010" data-slider-equal-steps="true" data-slider-snap="true" data-slider-theme="volume" />
                    </div>    
                </div>
            </td>
            <td  style="margin:0px; padding: 0px; border: 0px;" ></td>
        </tr>

    </table>


</div>

