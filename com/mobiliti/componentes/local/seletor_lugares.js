
/* =========================== VARIAVEIS GLOBAIS ================================*/
function LocalSelector()
{
    var value_indicador = new Array();
    var value_indicador_old = new Array();
    var load = false;
    var this_selector_element = null;
    var value_multiselect;
    var lazy_select = false;
    var lazy_array;
    var to_hide;

    var estados;
    var regioes;
    var areas_tematicas;
    var espacialidade_selecionada = -1;
    var cidades = new Array();

    //total de lugares escolhidos
    var total = 0;

    var skipLimit = false;

    this.html = function(idElement)
    {
        var button = '<div id="' + idElement + '" style="float: right;">'
                + '<div class="divCallOutLugares">'
                + '<button class="blue_button big_bt selector_popover" data-toggle="dropdown" style="margin-right: 27px !important; height: 34px; font-size: 14px;" rel="popover" >' + lang_mng.getString("selecionar") + '</button>'
                + '</div>'
                + '</div>';

        return button;
    }
    /* =========================== FIM VARIAVEIS GLOBAIS ================================*/

    var divSeletor = $('<div class="divSeletor">');

    /**
     * @param multiselect - Especifica se a lista de indicadores será de múltipla seleção ou de seleção simples
     */
    this.startSelector = function(multiselect, id_element_context, listener_param, orientation, _to_hide, skip)
    {
        this_selector_element = '#' + id_element_context;
        value_multiselect = multiselect;
        listener = listener_param;
        to_hide = '#' + _to_hide;
        skipLimit = skip;

        html = "";
        html += '<div><div class="box1 box"><h6 class="title_box">' + lang_mng.getString("seletor_espacialidade") + '</h6><ul class="nav nav-list list_menu_indicador lista1">' +
                '</ul></div>' +
                '<div class="box2 box">' +
                '<h6 class="title_box">' + lang_mng.getString("seletor_estado") + '</h6><ul class="nav nav-list list_menu_indicador lista2">' +
                '</ul></div>' +
                '<div class="box3 box"><img id="ui_city_loader" style="position:absolute; display:none; top: 45%; left: 55%;" src="img/loader.gif" /><h6 class="title_box"></h6><ul class="nav nav-list list_menu_indicador lista3">' +
                '</ul></div>';
        if (value_multiselect == true) {
            html += '<div class="itens_selecionados box"><a class="close lugar">&times;</a><h6 class="title_box">' + lang_mng.getString("seletor_selecionados") + '</h6><ul class="nav nav-list list_menu_indicador lista4"></ul></div></div>';
        }
        html += '</div>';
        html += '<div class="btn_select" style="display:none">';
        html += '<div class="messages"></div>';
        html += '<div class="buttons">';
        html += '<button class="blue_button small_bt btn_ok" type="button" style="font-size: 14px; height: 30px; font-family: helvetica; width: 38px; float: right;">Ok</button>';
        html += '<button class="gray_button big_bt btn_clean" id ="bt_limpar_selecionandos_lug" type="button" style="width:162px; margin-left: 20px; font-size: 14px; height: 30px;">' + lang_mng.getString("limpar_selecionados") + '</button><div>';
        html += '</div>';

        divSeletor.html(html);



        $(this_selector_element).find('.selector_popover').popover({
            html: true,
            trigger: 'manual',
            placement: orientation,
            delay: {show: 350, hide: 100},
            content: divSeletor.html()
        }).click(function(e) {
            $(this_selector_element).find('.messages').html("");
            $(to_hide).find('.divCallOut').removeClass('open');
            $(to_hide).find('.divCallOut .popover').css('display', 'none');
            startPopOver();
        });

        $('html').on('click.popover.divCallOutLugares', function(e)
        {
            if ($(e.target).has('.divCallOutLugares').length == 1)
            {
                $(this_selector_element).find('.divCallOutLugares .popover').hide();
                value_indicador = value_indicador_old.slice();
            }
        });
    }


    function startPopOver()
    {
        refresh();
        $(this_selector_element).find('.divCallOutLugares .popover').toggle();

        value_indicador_old = value_indicador.slice();

        if (load == false)
        {
            $(this_selector_element).find('.selector_popover').popover('show');

            loadData(listener);

            if (value_multiselect == true)
            {
                $(this_selector_element).find('div.divCallOutLugares .popover-inner,div.divCallOutLugares .popover-content,div.divCallOutLugares .popover').css('height', '425px');
                $(this_selector_element).find('div.divCallOutLugares .popover-inner,div.divCallOutLugares .popover-content,div.divCallOutLugares .popover').css('width', '703px');

                $(this_selector_element).find('.btn_select').css('width', '689px');

                $(this_selector_element).find('.btn_select').css('display', 'inline');

                $(this_selector_element).find('.btn_clean').click(function(e)
                {
                    value_indicador = new Array();

                    $(this_selector_element).find('.box3 ul li').removeClass('selected');
                    $(this_selector_element).find('.box2 ul li').removeClass('selected');


                    value_indicador_old = value_indicador.slice();
                    total = value_indicador.length;
                    fillSelectedItens();
                });

                $(this_selector_element).find('.btn_ok').click(function(e) {
                    $(this_selector_element).find('.divCallOutLugares .popover').toggle();

                    dispatchListener(listener);
                });
            }

            $(this_selector_element).find('.close').click(function(e) {
                $(this_selector_element).find('.divCallOutLugares .popover').hide();
                return 0;
            });
        }

        fillSelectedItens();


        if (espacialidade_selecionada == 7)
        {
            filterByBox2(areas_tematicas, espacialidade_selecionada);
        }
        else if (espacialidade_selecionada == 4)
        {
            filterByBox2(estados, espacialidade_selecionada);
        }
        else if (espacialidade_selecionada == 2)
        {
            filterByBox3(cidades);
        }


    }

    this.getData = function()
    {
        return value_indicador;
    }

    function dispatchListener(listener)
    {
        fillSelectedItens();

        var locais_municipal = new Array();
        var locais_estadual = new Array();
        var locais_area_tematica = new Array();

        $.each(value_indicador, function(i, item) {
            var local = new Local();
            local.id = item.id;
            local.n = item.n
            local.c = true;
            local.s = false;

            if (item.e == 2)
                locais_municipal.push(local);

            if (item.e == 4)
                locais_estadual.push(local);

            if (item.e == 7)
                locais_area_tematica.push(local);
        });

        var lugar_municipal = new Lugar();
        lugar_municipal.e = 2;
        lugar_municipal.ac = true;
        lugar_municipal.l = locais_municipal;

        var lugar_estadual = new Lugar();
        lugar_estadual.e = 4;
        lugar_estadual.ac = false;
        lugar_estadual.l = locais_estadual;

        var lugar_area_tematica = new Lugar();
        lugar_area_tematica.e = 7;
        lugar_area_tematica.ac = false;
        lugar_area_tematica.l = locais_area_tematica;

        listener([lugar_municipal, lugar_estadual, lugar_area_tematica]);
    }

    function fillSelectedItens()
    {
        var html = "";

        $.each(value_indicador, function(i, item) {
            html += "<li data-id=" + item.id + " data-texto='" + item.n + "'><a>" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.itens_selecionados .nav').html(html);
    }

    function loadData(listener)
    {
        load = true;

        $.getJSON('com/mobiliti/componentes/local/local.php', function(data) {
            fillData(data);
        });
    }

    function injetaEspacialidade(array, espacialidade)
    {
        $.each(array, function(i, item)
        {
            item.e = espacialidade;
        });

        return array;
    }

    function fillData(data)
    {
        estados = injetaEspacialidade(data.estados, 4);
        if (estados.length > 1)
        {
            var value = [];

            value.n = "Todos os estados";
            value.id = '-1';
            var newArray = [value];
            estados = newArray.concat(estados);
        }

        areas_tematicas = injetaEspacialidade(data.areasTematicas, 7);
        $.each(areas_tematicas, function(index, value) {
            geral.AddOrUpdateAreaTematica(value.id, value.n, value.tam);
        });

        fillFiltroBox1(getItensBox1());
    }

    function getItensBox1()
    {
        var array = new Array();
        var objeto;

        objeto = {};
        objeto.id = 4;
        objeto.n = lang_mng.getString("seletor_estadual");
        array.push(objeto);

        objeto = {};
        objeto.id = 2;
        objeto.n = lang_mng.getString("seletor_municipal");
        array.push(objeto);

        objeto = {};
        objeto.n = '[BROKER]';
        array.push(objeto);

        objeto = {};
        objeto.id = 7;
        objeto.n = lang_mng.getString("seletor_tematico");
        array.push(objeto);

        return array;
    }

    function fillFiltroBox1(array)
    {
        var html = "";
        $.each(array, function(i, item)
        {
            if (item.n == "[BROKER]")
                html += "<div style=\"margin-top: 10px; margin-bottom: 10px; border-top: 1px solid #999; width: 150px;\"></div>";
            else
                html += "<li data-id=" + item.id + "><a>" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.box1 .nav').html(html);

        $(this_selector_element).find('.box1 ul li').click(function(e)
        {
            $(this_selector_element).find('.box1 ul li').removeClass('active');
            $(this).addClass('active');

            $(this_selector_element).find('.box2 .nav').html('');
            $(this_selector_element).find('.box3 .nav').html('');

            var valorSelecionadoBox1 = parseInt($(this_selector_element).find('.box1 ul li.active').attr('data-id'));


            if (valorSelecionadoBox1 == 2)
            {
                estados[0].n = lang_mng.getString("seletor_todos_estados");
                $(this_selector_element).find('.box2 .title_box').html(lang_mng.getString("seletor_estado"));
                $(this_selector_element).find('.box3 .title_box').html(lang_mng.getString("seletor_municipios"));
            }
            else if (valorSelecionadoBox1 == 4)
            {
                estados[0].n = lang_mng.getString("marcar_todos");
                $(this_selector_element).find('.box2 .title_box').html(lang_mng.getString("seletor_estado"));
                $(this_selector_element).find('.box3 .title_box').html('');
            }
            else if (valorSelecionadoBox1 == 7)
            {
                $(this_selector_element).find('.box2 .title_box').html(lang_mng.getString("seletor_areas"));
                $(this_selector_element).find('.box3 .title_box').html('');
            }
            filtroBox2($(this).attr('data-id'));

            espacialidade_selecionada = valorSelecionadoBox1;
        });
    }

    function filtroBox2(value)
    {
        if (value == 7)
            filterByBox2(areas_tematicas, value);
        else
            filterByBox2(estados, value);
    }

    function filterByBox2(array, value)
    {
        $(this_selector_element).find('.box2 ul li').removeClass('active');
        $(this_selector_element).find('.box2 ul li').removeClass('selected');

        var html = "";

        $.each(array, function(i, item)
        {
            var htmlespacialidade = ((value == 2) ? "" : "data-espacialidade=" + item.e);
            if (value == 4 || value == 7)
                var classItem = ((contains(item) == true) ? 'class="selected"' : '');
            html += "<li " + htmlespacialidade + " data-id=" + item.id + " data-texto='" + item.n + "'" + classItem + " data-tematica=" + item.tam + "><a>" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.box2 .nav').html(html);

        if (value == 4)
        {
            $(this_selector_element).find('.box2 ul li').click(function(e)
            {
                var valorSelecionado = parseInt($(this).attr('data-id'));

                if (valorSelecionado == -1)
                {
                    $.each($(this_selector_element).find('.box2 ul li'), function(pos, itemList)
                    {
                        var id = parseInt($(this).attr('data-id'));
                        if (id == -1)
                            return;
                        var objeto = {};
                        objeto.id = id;
                        objeto.n = $(this).text();
                        objeto.e = 4; //espacialidade estadual

                        adicionaElemento(objeto, $(this));
                    });
                }
                else
                {
                    var objeto = {};
                    objeto.id = valorSelecionado;
                    objeto.n = $(this).text();
                    objeto.e = 4; //espacialidade estadual

                    if ($(this).hasClass('selected') == false)
                    {
                        adicionaElemento(objeto, $(this));
                    }
                    else
                    {
                        removeElemento(objeto);
                        $(this).removeClass('selected');
                    }
                }
            });
        }
        else if (value == 7)
        {
            $(this_selector_element).find('.box2 ul li').click(function(e)
            {
                var valorSelecionado = parseInt($(this).attr('data-id'));
                var tamanho_tematica = parseInt($(this).attr('data-tematica'));

                var objeto = {};
                objeto.id = valorSelecionado;
                objeto.n = $(this).text();
                objeto.e = value;

                if ($(this).hasClass('selected') == false)
                {
                    adicionaElemento(objeto, $(this), tamanho_tematica);
                }
                else
                {
                    removeElemento(objeto, tamanho_tematica);
                    $(this).removeClass('selected');
                }

            });
        }
        else if (value == 2)
        {
            $(this_selector_element).find('.box2 ul li').click(function(e)
            {
                $(".box3 ul").empty();
                $("#ui_city_loader").show();


                var objeto = {};
                objeto.id = parseInt($(this).attr('data-id'));
                objeto.n = $(this).text();
                objeto.e = 4; //espacialidade estadual

                $(this_selector_element).find('.box2 ul li').removeClass('active');
                $(this).addClass('active');

                filtroBox3($(this).attr('data-id'));
            });
        }
    }

    function filterByBox3(array)
    {

        $("#ui_city_loader").hide();

        array = injetaEspacialidade(array, 2);

        if (array.length > 1)
        {
            var value = [];
            value.n = lang_mng.getString("marcar_todos");
            value.id = '-1';
            var newArray = [value];
            array = newArray.concat(array);
        }

        var html = "";

        $.each(array, function(i, item)
        {
            var classItem = ((contains(item) == true) ? 'class="selected"' : '');
            html += "<li data-espacialidade=" + item.e + " data-id=" + item.id + " data-texto='" + item.n + "'" + classItem + "><a>" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.box3 .nav').html(html);

        listenerClickItens();
    }

    function filtroBox3(value)
    {
        $.getJSON('com/mobiliti/componentes/local/cidades_por_estado.php', {estado: value}, function(data) {
            cidades = data.cidades;
            filterByBox3(data.cidades)
        });
    }

    function adicionaItemTodos(array)
    {
        var html = "";

        if (array.length > 1)
        {
            var value = [];
            value.nome = "Todos";
            value.nivel = "2";
            value.id = '-1';
            var newArray = [value];
            array = newArray.concat(array);
        }
    }



    /**
     * @param value_multiselect - Informa se a lista é de múltiplca seleção ou de simples seleção
     * Habilita o evento de click na lista.
     */
    function listenerClickItens()
    {
        if (value_multiselect == false)
        {
            $(this_selector_element).find('.box3 ul li').click(function(e)
            {
                $(this_selector_element).find('.box3 ul li').removeClass('active');
                $(this).addClass('active');

                $(this_selector_element).find('div.divCallOutLugares .popover').hide();

                var objeto = {};//getIndicadorById(parseInt($(this).attr('data-id')));
                objeto.id = parseInt($(this).attr('data-id'));

                value_indicador[0] = objeto;

                fillLabelButtonIndicador();

                dispatchListener(listener);
            });
        }
        else
        {
            $(this_selector_element).find('.box3 ul li').click(function(e)
            {
                var valorSelecionado = parseInt($(this).attr('data-id'));

                if (valorSelecionado == -1)
                {
                    $(this_selector_element).find('.box3 ul li[data-id=-1]').removeClass('selected');

                    var itens = new Array();

                    $.each($(this_selector_element).find('.box3 ul li'), function(pos, itemList)
                    {
                        var id = parseInt($(this).attr('data-id'));
                        if (id == -1)
                            return;
                        var objeto = {};
                        objeto.id = id;
                        objeto.n = $(this).text();
                        objeto.e = 2; //espacialidade mun
                        if (contains(objeto) == false)
                            itens.push(objeto);

                    });

                    adicionaVariosElementos(itens);
                }
                else
                {
                    var objeto = {};//getIndicadorById(parseInt($(this).attr('data-id')));
                    objeto.id = parseInt($(this).attr('data-id'));
                    objeto.n = $(this).text();
                    objeto.e = 2; //espacialidade municipal

                    if ($(this).hasClass('selected') == false)
                    {
                        adicionaElemento(objeto, $(this));
                    }
                    else
                    {
                        removeElemento(objeto);
                        $(this).removeClass('selected');
                    }
                }
            });
        }
    }

    function fillLabelButtonIndicador()
    {
        var objeto = value_indicador[0];
        textoIndicadorSelecionado = objeto.nome;

        if (textoIndicadorSelecionado.length > 8)
            textoIndicadorSelecionado = textoIndicadorSelecionado.slice(0, 8) + '...';

        $(this_selector_element).find('.selector_popover').html(textoIndicadorSelecionado);
        $(this_selector_element).find('.selector_popover').prop('title', objeto.nome);
    }


    /**
     * @description Verifica se o indicador está no array de indicadores selecionados
     */
    function contains(value)
    {
        var length = value_indicador.length
        for (var i = 0; i < length; i++)
        {
            if (value_indicador[i].id == value.id && value_indicador[i].e == value.e)
                return true;
        }
        return false;
    }

    /**
     * Adiciona o indicador a lista de indicadores selecionados.
     * Verifica antes se o item já não está na lista
     */
    function adicionaElemento(value, elemento, size)
    {
        $(this_selector_element).find('.messages').html("");

        if (size == undefined || size == null)
            size = 0;

        if (contains(value) == false)
        {
            if (!skipLimit)
            {
                var idc = geral.getIndicadores().length;
                var lug;
                if (size == 0)
                    lug = (total + 1);
                else
                    lug = (total + size);
                var produto = idc * lug;

                if (produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
                {
                    var message = '<div class="alert">';
                    message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'

                    var msg = lang_mng.getString("seletor_lim_tab");
                    msg = msg.replace("$1", idc);
                    msg = msg.replace("$2", lug);
                    msg = msg.replace("$3", produto);
                    message += msg;

                    message += '</div>';
                    $(this_selector_element).find('.messages').html(message);
                }
                else if (produto >= JS_LIMITE_DOWN)
                {
                    var message = '<div class="alert">';
                    message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'

                    var msg = lang_mng.getString("seletor_lim_down");
                    msg = msg.replace("$1", JS_LIMITE_DOWN);
                    message += msg;


                    message += '</div>';
                    $(this_selector_element).find('.messages').html(message);
                    return 0;
                }
            }

            total = lug;
            value_indicador.push(value);
            elemento.addClass('selected');
            fillSelectedItens();

        }
    }

    function adicionaVariosElementos(value)
    {
        $(this_selector_element).find('.messages').html("");

        if (!skipLimit)
        {

            var idc = geral.getIndicadores().length;
            var lug = total + value.length;
            var produto = idc * lug;


            if (produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'

                var msg = lang_mng.getString("seletor_lim_tab");
                msg = msg.replace("$1", idc);
                msg = msg.replace("$2", lug);
                msg = msg.replace("$3", produto);
                message += msg;


                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
            }
            else if (produto >= JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'

                var msg = lang_mng.getString("seletor_lim_down");
                msg = msg.replace("$1", JS_LIMITE_DOWN);
                message += msg;

                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
                return 0;
            }
        }

        total = lug;
        $(this_selector_element).find('.box3 ul li').addClass('selected');
        value_indicador = value_indicador.concat(value);
        fillSelectedItens();

    }
    /**
     * Remove um indicador da lista
     */
    function removeElemento(value, size)
    {
        var length = value_indicador.length;
        if (size == undefined || size == null)
            size = 0;

        for (var i = 0; i < length; i++)
        {
            if (value_indicador[i].id == value.id)
            {
                value_indicador.splice(i, 1);
                fillSelectedItens();
                break;
            }
        }

        if (size > 0)
            total -= size;
        else
            total -= 1;


        $(this_selector_element).find('.messages').html("");

        if (!skipLimit)
        {
            var idc = geral.getIndicadores().length;
            var lug = total;
            var produto = idc * lug;

            if (produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'


                var msg = lang_mng.getString("seletor_lim_tab");
                msg = msg.replace("$1", idc);
                msg = msg.replace("$2", lug);
                msg = msg.replace("$3", produto);
                message += msg;


                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
            }
            else if (produto >= JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'

                var msg = lang_mng.getString("seletor_lim_down");
                msg = msg.replace("$1", JS_LIMITE_DOWN);
                message += msg;

                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
                return 0;
            }
        }

    }

    /**
     * @description Pega o objeto da lista de indicadores a partir do id
     */
    function getIndicadorById(value)
    {
        var length = array_indicadores.length;

        for (var i = 0; i < length; i++)
        {
            var item = array_indicadores[i];
            if (item.id == value)
                return item;
        }
    }

    /**
     * @description Pega o objeto da lista de indicadores a partir da sigla
     */
    function getIndicadorBySigla(value)
    {
        var length = array_indicadores.length;
        for (var i = 0; i < length; i++)
        {
            var item = array_indicadores[i];
            if (item.sigla == value)
                return item;
        }
    }

    function convertToArray(value)
    {
        if ($.isArray(value))
            return value;
        else
            return [value];
    }

    this.setLugares = function(lugares)
    {
        setLugaresValue(lugares);
    }

    function setLugaresValue(lugares)
    {
        var array = new Array();

        $.each(lugares, function(i, item)
        {
            var locais = item.l;
            var espacialidade = item.e;

            $.each(locais, function(k, local)
            {
                local.e = espacialidade;

                array.push(local);
            });
        });

        value_indicador = array;
    }


    this.refresh = function()
    {
        refresh();
    }

    function refresh()
    {
        setLugaresValue(geral.getLugares().slice());
    }
}