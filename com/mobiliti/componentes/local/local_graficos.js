var dataLocal = null;

function SeletorLocalG()
{
//    console.log('============');
//    console.log('SELETORLOCAL');
    var itens_selecionados = new Array();
    var listener;
    var element_context;
    var lazy_select = false;
    var lazy_array;
    var mult_espacializacao;
    var espacialidade_selecionada = 0;

    //adicionando por reinaldo
    var listenerSelectionItem = null;
    this.setListenerSelectionItem = function(_listener) {
        console.log('setListenerSelectionItem');
        listenerSelectionItem = _listener;
    }

    this.setButton = function(html) {
        console.log('setButton');
        $(element_context).find('.button').html(html);
    }

    this.getEspacialidade_selecionada = function() {
        console.log('getEspacialidade_selecionada');
        return espacialidade_selecionada;
    }

    this.selectedElement = function(value) {
        console.log('selectedElement');
        try
        {
            var string_id = '[data-id=' + value + ']';
            var $element = $(element_context).find('.local .title_toggle').next().find(string_id);

            $(element_context).find('.local li').removeClass('selection');
            $element.addClass('selection');
            var top = 438 - $(element_context).find('.local').scrollTop();

            value = $element.offset().top - top;
            $(element_context).find('.local').animate({
                scrollTop: value
            }, 'slow');

        }
        catch (err)
        {
        }
    }

    function dispatcher(array) {
        console.log('dispatcher');
        $.each(itens_selecionados, function(i, item)
        {
//            console.log('item.id: ' + item.id);
            if (item.e == espacialidade_selecionada)
                item.ac = true;
            else
                item.ac = false;

        });
//        console.log('listener: '+listener);
        listener(itens_selecionados);
    }

    this.startLocal = function(listener_value, context, multipla_espacializacao) {
        console.log('startLocal');
//        console.log('listener_value: '+listener_value);
        listener = listener_value;

        element_context = '#' + context;
        mult_espacializacao = multipla_espacializacao;

        //Quando clicar em uma espacialidade
        $(element_context).find('.title_toggle').click(function() {
            espacialidade_selecionada = parseInt($(this).attr("data-espacialidade"));
            $(element_context).find('.title_toggle').removeClass('selected_item');
            $(element_context).find('.list_local').fadeOut();
            $(element_context).find('.inputSearchLocal').val('');
            $(this).toggleClass('selected_item');
            $(this).next().fadeIn();
            if (mult_espacializacao == false) {
                dispatcher(itens_selecionados);
            }
        });

        enableBtnCleanAll();
        enabledBtnAll();
    }

    function addEspacialidade(array, espacialidade) {
        console.log('addEspacialidade');
        $.each(array, function(i, item)
        {
            item.e = espacialidade;
        });
    }

    function enableBtnCleanAll() {
        console.log('enableBtnCleanAll');
        $(element_context).find('.btn_clean_local').removeAttr("disabled");
        $(element_context).find('.btn_clean_local').click(function()
        {
            $.each(itens_selecionados, function(i, item)
            {
                $.each(item.l, function(k, local)
                {
                    local.c = false;
                    local.s = false;
                });
            });
            $(element_context).find('.list_local li.selected').removeClass('selected');
            dispatcher(itens_selecionados);
        });
    }

    function enabledBtnAll() {
        console.log('enabledBtnAll');
        $(element_context).find('.btn_all').removeAttr("disabled");
        $(element_context).find('.btn_all').click(function()
        {
            $.each(itens_selecionados, function(i, item)
            {
                $.each(item.l, function(k, local) {
                    local.c = true;
                    local.s = false;
                });
            });

            $(element_context).find('.list_local li').addClass('selected');

            dispatcher(itens_selecionados);
        });
    }

    function setEspacialidadePorDadosPreenchidos() {
        console.log('setEspacialidadePorDadosPreenchidos');
        var countMunicipal;
        var countEstadual;
//        var countAreaTematica;

        lista = itens_selecionados;
//        console.log('lista.length: '+lista.length); //2

        for (var i = 0; i < lista.length; i++)
        {
            var locais = lista[i].l;
            if (lista[i].e == 2)
                countMunicipal = locais.length;
            else if (lista[i].e == 4)
                countEstadual = locais.length;

//            console.log('countMunicipal: '+countMunicipal); //2
//            else if(lista[i].e == 7)
//                countAreaTematica = locais.length;
        }


        if (espacialidade_selecionada == 0)
        {
//            if(countAreaTematica > 0)
//                espacialidade_selecionada = 7;
            if (countMunicipal > 0)
                espacialidade_selecionada = 2;
            else if (countEstadual > 0)
                espacialidade_selecionada = 4;
        }

        var string = "";

        //index 0    value 2
        //index 1    value 4
        //index 2    value 7
        $.each([2, 4, 7], function(index, value)
        {
//            console.log('index: '+index);
//            console.log('value: '+value);
            string = '.title_toggle[data-espacialidade=' + value + ']';
            if (espacialidade_selecionada != value)
            {
                $(element_context).find(string).removeClass('selected_item');
                $(element_context).find(string).next().fadeOut();
            }
            else
            {
                $(element_context).find(string).addClass('selected_item');
                $(element_context).find(string).next().fadeIn();
            }
        });
    }

    this.refresh = function() {
        console.log('this.refresh - local');
        itens_selecionados = geral.getLugares();
//        console.log('itens_selecionados: '+itens_selecionados);
        setEspacialidadePorDadosPreenchidos();
        preencheLista();
    }

    function preencheLista() {
        console.log('preencheLista');
        $("#head_list_c").hide();
        var display_cidade = false;

        $("#head_list_e").hide();
        var display_estado = false;

//        $("#head_list_at").hide();
//        var display_tematica = false;


        $.each(itens_selecionados, function(i, item)
        {

            if (item.e == 2)
            {
                preencheCidades(item.l);
                if (item.l.length != 0 && item.l != undefined && item.l != null)
                    display_cidade = true;
            }
            else if (item.e == 4)
            {
                preencheEstados(item.l);
                if (item.l.length != 0 && item.l != undefined && item.l != null)
                    display_estado = true;
            }
//            else if(item.e == 7)
//            {
//                preencheAreasTematicas(item.l);
//                if( item.l.length != 0 && item.l != undefined && item.l != null) display_tematica = true;
//            }
        });


        if (display_cidade)
            $("#head_list_c").show();
        if (display_estado)
            $("#head_list_e").show();
//        if(display_tematica)$("#head_list_at").show();


        enabledSelected();
    }

    function enabledSelected()
    {
        console.log('enableSelected');
        $(element_context).find('.list_local li').click(function()
        {

            $(element_context).find('.list_local li').removeClass('selection');
            $(this).addClass('selection');

            var objeto = {};
            objeto.id = parseInt($(this).attr('data-id'));
            objeto.e = parseInt($(this).attr('data-espacialidade'));
            objeto.nome = $(this).text();

            //Pra ficar mais rápido chama o listener diretamente    
            if (listenerSelectionItem == null)
                dispatcher(itens_selecionados);
            else
                listenerSelectionItem(objeto.id, objeto.e);
        });
//        var tam = 0;
//        console.log('element_context: '+element_context);
//         tam = $.each('.box2 ul li selected').length;
         
         
//         tam = $('.list_local_cidades').find('.selected').length;
//        $(element_context).find('.list_local li selected'), function(i,item){
//            console.log(item.id);
//        }
//        console.log('tam: '+tam);
        
//        for(var i = 0; i < itens_selecionados.length; i++){
//            if($(this).parent().hasClass('selected') == true){
//                tam++;
//            }
//        }

//        $.each(itens_selecionados,function(i,item){
//                    console.log(item);
//                });
        
//        $.each(itens_selecionados, function(i, item)
//            {
//                $.each(item.l, function(k, local)
//                {
//                    local.c = false;
//                    local.s = false;
//                });
//            });
        
        var locais = geral.getLugaresPorEspacialidadeAtiva();
        var itensSelecionadosAtivos = new Array();
        console.log('locais: '+locais);
        if (!(locais === undefined || locais === null || locais === ""))
        {
            for (var i = 0; i < locais.l.length; i++)
            {
                var lugar = locais.l[i];
                if (lugar)
                {
                    console.log('lugar.c: '+lugar.c);
                    if(lugar.c){
                        itensSelecionadosAtivos.push(lugar.id);
                    }
                }
            }
        }        
        console.log('itensSelecionadosAtivos: '+itensSelecionadosAtivos.length);

        //Ao clicar no botão de selecionar na lista de cidades ou municípios
        $(element_context).find('.icon_select').click(function()
        {
            var objeto = {};
            objeto.id = parseInt($(this).parent().attr('data-id'));
            objeto.e = $(this).parent().attr('data-espacialidade');
            espacialidade_selecionada = objeto.e;
            objeto.nome = $(this).parent().text();
            if ($(this).parent().hasClass('selected') == false)
            {
                if (itensSelecionadosAtivos.length < 10) {
                    var obj = getElement(objeto);
                    obj.c = true;
                    $(this).parent().addClass("selected");
                }
                dispatcher(itens_selecionados);
            }
            else
            {
//                console.log('selected true');
                var obj = getElement(objeto);
                obj.c = false;
                $(this).parent().removeClass('selected');

                dispatcher(itens_selecionados);
            }
        });

        //Ao clicar no botão de remover na lista de cidades ou municípios
        $(element_context).find('.icon_remove').click(function()
        {
            var objeto = {};
            objeto.id = parseInt($(this).parent().attr('data-id'));
            objeto.e = $(this).parent().attr('data-espacialidade');
            espacialidade_selecionada = objeto.e;
            objeto.nome = $(this).parent().text();

            $(this).parent().removeClass('selected');
            removeElement(objeto);

            dispatcher(itens_selecionados);
        });

    }

    function classLi(item) {
        console.log('classLi');
        if (item.c == true && item.s == true)
            return "class='selected selection'";
        else if (item.c == true)
            return "class='selected'";
        else if (item.s == true)
            return "class='selection'";
        return "";
    }

    function preencheCidades(cidades) {
        console.log('preencheCidades');
        var html = "";
//        console.log('cidade.length: '+cidades.length); 
        var cont = 0;
        for (var i = 0, il = cidades.length; i < il; i++)
        {
            var item = cidades[i];
//            console.log('item: '+item);
//            if(cont < 2){
//                console.log()
            var classSelected = classLi(item);
//                console.log('classSelected: '+classSelected);
            html += '<li data-espacialidade=2 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>' + item.n + '</li>';
//            }
//            else{
//                html += '<li data-espacialidade=2 data-id=' + item.id + '><div class="icon_select"></div><div class="icon_remove"></div>'  + item.n + '</li>' ;
//            }

//            cont++;
        }

        $(element_context).find('.list_local_cidades').html(html);
    }


//    function preencheAreasTematicas(areasTematicas){
////        console.log('preencheAreasTematicas');
//        var html = "";
//
//        $.each(areasTematicas,function(i,item)
//        {
//            var classSelected = classLi(item);
//
//            html += '<li data-espacialidade=7 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>' + item.n + '</li>';
//        });
//
////        $(element_context).find('.list_local_areas_tematicas').html(html);
//    }

    function preencheEstados(estados) {
        console.log('preencheEstados');
        var html = "";

        $.each(estados, function(i, item)
        {
            var classSelected = classLi(item);

            html += '<li data-espacialidade=4 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>' + item.n + '</li>';
        });

        $(element_context).find('.list_local_estados').html(html);
    }

    /**
     * @description
     */
    function getElement(value) {
        console.log('getElement');
        lista = itens_selecionados;
//        console.log('lista.length: ' + lista.length);

        for (var i = 0; i < lista.length; i++)
        {
//            console.log('parseInt(lista[' + i + '].e): ' + parseInt(lista[i].e));
//            console.log('parseInt(value.e): ' + parseInt(value.e));
            if (parseInt(lista[i].e) == parseInt(value.e))
            {
                var locais = lista[i].l;

                for (var k = 0; k < locais.length; k++)
                {
//                    console.log('locais[k].id: ' + locais[k].id);
//                    console.log('value.id: ' + value.id);

                    if (locais[k].id == value.id)
                        return locais[k];
                }
            }
        }
    }

    /**
     * @description 
     */
    function removeElement(value) {
        console.log('removeElement');
        lista = itens_selecionados;

        for (var i = 0; i < lista.length; i++)
        {
            if (parseInt(lista[i].e) == parseInt(value.e))
            {
                var locais = lista[i].l;

                for (var k = 0; k < locais.length; k++)
                {
                    if (locais[k].id == value.id)
                    {
                        locais.splice(k, 1);
                    }
                }
            }
        }
    }

    /**
     * @description Pega o objeto da lista pelo name
     */
    function getItemByName(value) {
        console.log('getItemByName');
        var cidades = dataLocal.cidades;
        var estados = dataLocal.estados;

        for (var i = 0; i < cidades.length; i++)
        {
            var item = cidades[i];
            if (padronizaNome(item.nome) == value.nome && item.e == value.e)
                return item;
        }

        for (var i = 0; i < estados.length; i++)
        {
            var item = estados[i];
            if (padronizaNome(item.nome) == value && item.e == value.e)
                return item;
        }
    }

    function convertToArray(value) {
        console.log('convertToArray');
        if ($.isArray(value))
            return value;
        else
            return [value];
    }

    this.setItensSelecionados = function(array_values) {
        console.log('setItensSelecionados');
        setItens(array_values);
    }

    this.getItensSelecionados = function() {
        console.log('getItensSelecionados');
        return itens_selecionados;
    }

    function setItens(array_values) {
        console.log('setItens');
        itens_selecionados = array_values;
        setEspacialidadePorDadosPreenchidos();
        preencheLista();
        dispatcher(itens_selecionados);
    }

}