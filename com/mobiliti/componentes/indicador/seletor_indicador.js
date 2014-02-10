var dataSeletorIndicador = null;

/* =========================== VARIAVEIS GLOBAIS ================================*/
function IndicatorSelector()
 {
    var value_indicador = new Array();
    var value_indicador_old = new Array();
    var load = false;
    var array_indicadores;
    var array_temas;
    var array_dimensoes;
    var array_indicadores_has_temas;
    var this_selector_element = null;
    //var lazy_select = false;
    var lazy_array;
    var multiYear = false;
    var divSeletor = $('<div class="divSeletor">');
    var listener = null;
    
    var to_hide;
    var skipLimit  = false;

    this.html = function(idElement)
    {
       var button = '<div id="' + idElement + '" style="float: right;">'
            + '<div class="divCallOut">'
            + '<button class="blue_button big_bt selector_popover" data-toggle="dropdown" style="float:right; margin-right: 29px !important; height: 34px; font-size: 14px;" rel="popover" >Selecionar</button>'
            + '</div>'
        + '</div>';

        return button;
    };

    /**
    * @param multiselect - Especifica se a lista de indicadores serÃ¡ de mÃºltipla seleÃ§Ã£o ou de seleÃ§Ã£o simples
    */
    this.startSelector = function(multiselect, id_element_context, _listener, orientation, multiYear_,_to_hide,_skipLimit)
    { 
        listener = _listener;
        multiYear = multiYear_;

        this_selector_element = '#' + id_element_context;
        
        to_hide = '#' + _to_hide;
        skipLimit = _skipLimit;
        
        html = "";
        html += '<div><div class="dimensao box dim"><h6 class="title_box">Dimensão</h6><ul class="nav nav-list list_menu_indicador dim">' +
              '</ul></div>' +
              '<div class="tema box">'+
              '<h6 class="title_box">Tema</h6><ul class="nav nav-list list_menu_indicador">' +
              '</ul></div>' +
              '<div class="indicador box"><h6 class="title_box">Indicador</h6><ul class="nav nav-list list_menu_indicador">' +
              '</ul></div>';
        if(multiselect == true){
              html += '<div class="itens_selecionados box"><a class="close indicador">&times;</a><h6 class="title_box">Selecionados</h6><ul class="nav nav-list list_menu_indicador"></ul></div></div>';
        }
        html+='</div>';
        html += '<div class="btn_select" style="display:none">';
        html += '<div class="messages"></div>';
        html += '<div class="buttons">';
        html += '<button class="blue_button big_bt btn_ok" type="button" style="float: right; font-size: 14px; height: 30px; padding: 5px 10px;">Ok</button>';
        html += '<button class="gray_button big_bt btn_clean" type="button" style="font-size: 14px; height: 30px; margin-left: 20px;">Limpar selecionados</button><div>';
        html += '</div>';
        
        divSeletor.html(html);
        
        
        
        $(this_selector_element).find('.selector_popover').popover(
        {
            html:true,
            trigger:'manual',
            placement : orientation,
            delay: { show: 350, hide: 100 },
            content : divSeletor.html()
        }).click(function(e) {
            $(this_selector_element).find('.messages').html("");
            $(to_hide).find('.divCallOutLugares').removeClass('open');
            $(to_hide).find('.divCallOutLugares .popover').css('display','none');
            
            startPopOver(multiselect);
        });
        closePopOver();
        
    };
    
    this.refresh = function()
    {
        refresh();
    };

    this.setIndicadores = function setIndicadores(array_values)
    {
        setIndicadoresValue(array_values);
    };
        
    function startPopOver(multiselect)
    {

        $(this_selector_element).find('.messages').html("");
        refresh();
        
        $(this_selector_element).find('.divCallOut .popover').toggle();
       
        value_indicador_old = value_indicador.slice();
       
        if(load == false)
        { 
            $(this_selector_element).find('.selector_popover').popover('show');

            loadData(multiselect,listener);
        
            if(multiselect == true)
            {
                $(this_selector_element).find('div.divCallOut .popover-inner,div.divCallOut .popover-content,div.divCallOut .popover').css('height','360px');
                $(this_selector_element).find('div.divCallOut .popover-inner,div.divCallOut .popover-content,div.divCallOut .popover').css('width','713px');
                
                $(this_selector_element).find('.btn_select').css('width','699px');
                $(this_selector_element).find('.btn_select').css('display','inline');
                
                $(this_selector_element).find('.btn_clean').click(function(e){
                    
                    //$(this_selector_element).find('.divCallOut .popover').toggle();
                    
                    value_indicador = new Array();

                    $(this_selector_element).find('.indicador ul li').removeClass('selected');
                    $(this_selector_element).find('.indicador ul li .indicador_ano span').removeClass('selected');
                    value_indicador_old = value_indicador.slice();
                    fillSelectedItens();
                });
                
                $(this_selector_element).find('.btn_ok').click(function(e){
                    $(this_selector_element).find('.divCallOut .popover').toggle();
                    
                    dispatchListener();
                });
            }
            
            $(this_selector_element).find('.close').click(function(e){
                $(this_selector_element).find('div.divCallOut .popover').hide();
                return 0;    
            });
        }
        fillSelectedItens();
        fillSelectedItensOfCurrentListOfIndicador();
    }
    
    function dispatchListener()
    {
       listener(value_indicador);  
       //fillSelectedItens(); 
    }

    function fillSelectedItensOfCurrentListOfIndicador()
    {

        $(this_selector_element).find('.indicador ul li').removeClass('selected');
        $(this_selector_element).find('.indicador ul li .indicador_ano span').removeClass('selected');
        
        var indicadoresDaListaAtual = new Array();

        $(this_selector_element).find('.indicador ul li').each(function(i,item)
        {
            var value = parseInt($(this).attr('data-id'));
            indicadoresDaListaAtual.push(value);
        });

        $.each(indicadoresDaListaAtual,function(i,value)
        {
            var elementLi = '.indicador ul li[data-id=' + value + ']';

            $li = $(this_selector_element).find(elementLi);
            
            var array = getArrayOfIndicadores(value);
           
            if(array.length >= 1)
                $li.addClass('selected');

            $.each(array,function(i,item)
            {
                var element = '.indicador_ano span[data-id=' + item.a + ']'; 
                
                $li.find(element).addClass('selected');    
            });
        });

        
    }

    function fillSelectedItens()
    {
        var indicadoresDistintos = getIndicadoresDistintos(value_indicador);
        
        var html = "";
        
        $.each(indicadoresDistintos,function(i,item)
        {
            var array = getArrayOfIndicadores(item.id);
            var classItem = ((array.length >=1 ) ? 'class="selected"' : '');
            var classYear = getDivAno(array, item.id);
            
            html += "<li data-id='" + item.id + "' data-desc='" + item.desc +"' data-sigla='" + item.sigla + "' " + classItem +"><a title='" + item.desc + "'>" + item.nc + "</a>" + classYear + "</li>";
        });
        
        $(this_selector_element).find('.itens_selecionados .nav').html(html); 


        enableClickYear();
        $(this_selector_element).find(".itens_selecionados ul li a").tooltip({delay: 500});
    }

    function enableClickYear()
    {
        $(this_selector_element).find(".itens_selecionados ul li .indicador_ano span").click(function()
        {
            var idIndicadorSelecionado = parseInt($(this).attr('data-indicador'));
            var anoSelecionado = parseInt($(this).attr('data-id'));
            
            var indicadorEmAnos = getArrayOfIndicadores(idIndicadorSelecionado);

            if($(this).hasClass('selected') == true)
            {
                if(indicadorEmAnos.length > 1)                
                {
                    $(this).removeClass('selected');
                    var objeto = getIndicadorById(idIndicadorSelecionado);
                    objeto.a = anoSelecionado;
                    removeIndicador(objeto);
                }
            }
            else
            {
                //$(this).parent().parent().addClass("selected");

                var objeto = getIndicadorById(idIndicadorSelecionado);
                objeto.a = anoSelecionado;
                adicionaIndicador(objeto,$(this));
            }
        });
    }


    /**
    * Carrega a lista de dimensÃµes da primeira coluna
    */
    function loadData(multiselect)
    {
        load = true;
       
        if(dataSeletorIndicador == null)
        {
            $.getJSON('com/mobiliti/componentes/indicador/filtros.php', function(data){  
                dataSeletorIndicador = data;
                fillData(data,multiselect);
           });    
        }
        else
        {
            fillData(dataSeletorIndicador,multiselect);
        }
    }


    function fillData(data,multiselect)
    {
        array_indicadores = data.indicadores;
        array_dimensoes = data.dimensoes;
        array_temas = data.temas;
        array_indicadores_has_temas = data.var_has_tema;
        
        /*if(lazy_select)
        {
            setIndicadoresValue(lazy_array);
            value_indicador_old = value_indicador.slice();
            lazy_select = false;
        }*/

        var html = "";

        $.each(array_dimensoes,function(i,item)
        {
            html += "<li data-id=" + item.id + "><a>" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.dimensao .nav').html(html); 

        $(this_selector_element).find('.dimensao ul li').click(function(e){
            $(this_selector_element).find('.dimensao ul li').removeClass('active');
            $(this).addClass('active');
            
            $(this_selector_element).find('.tema .nav').html(''); 
            $(this_selector_element).find('.indicador .nav').html(''); 
            filtro_tema($(this).attr('data-id'),multiselect);
        });
    }

    /**
    * Filtra os temas pela dimensao.
    * Caso nÃ£o existam temas para a dimensÃ£o selecionada serÃ£o carregados os indicadores da mesma
    */
    function filtro_tema(value,multiselect)
    {
        var temas = getTemasPorDimensao(value);
        
        if(temas.length == 0)
        {
            var indicadores = getIndicadoresPorTema(value);   
            fillTemas(new Array());
            fillIndicadores(indicadores,multiselect);
        }
        else
        {
            fillTemas(temas);
        }

        $(this_selector_element).find('.tema ul li').click(function(e)
        {
            $(this_selector_element).find('.tema ul li').removeClass('active');
            $(this).addClass('active');  
            
            if($(this).attr('data-id') == -1)
                filtro_indicador($(this_selector_element).find('.dimensao .nav .active').attr('data-id'), multiselect);    
            else
                filtro_indicador($(this).attr('data-id'),multiselect);
        });
    }

    function getTemasPorDimensao(temaSuperior)
    {
        var lista = new Array();
        
        $.each(array_temas,function(i,item){
            if(item.tema_superior == temaSuperior)
                lista.push(item);
        });
        
        return lista;
    }

    function getIndicadoresPorTema(value)
    {
        var listaIndicadorHasTema = new Array();
        var listaIndicadores = new Array();

        $.each(array_indicadores_has_temas,function(i,item)
        {
            if(item.tema == value){
                listaIndicadorHasTema.push(item);
            }
        });
        
        $.each(array_indicadores,function(i,item)
        {
            if(containsInFilter(listaIndicadorHasTema,item.id) == true)
            {
                listaIndicadores.push(item);
            }
        });

        return listaIndicadores;
    }

    /**
    * Passa uma lista que contem os indicadores de certo tema e verifica se o indicador passado como parÃ¢metro estÃ¡ na lista
    */
    function containsInFilter(listaIndicadorHasTema,idIndicador)
    {
        for(var i = 0; i < listaIndicadorHasTema.length; i++)
        {   
            if(listaIndicadorHasTema[i].variavel == idIndicador)
                return true;
        } 
        return false;
    }

    /**
    * Filtra os indicadores pelo tema selecionado
    */
    function filtro_indicador(value,multiselect)
    {
        var indicadores = getIndicadoresPorTema(value);
        fillIndicadores(indicadores,multiselect);
    }
    
    function adicionaOpcaoTodos(array)
    {
        var value = new IndicadorPorAno();

        value.desc = "Selecionar todos";
        value.nc = "Selecionar todos";
        value.sigla = "Selecionar todos";

        value.id = '-1';
        var newArray = [value];
        array = newArray.concat(array);

        return array;
    }

    /**
    * @param array - Recebe um array de valores, com propriedades nome e id
    * multiselect Especifica se a lista serÃ¡ de mÃºltipla seleÃ§Ã£o ou de seleÃ§Ã£o simples
    * Preenche a lista de indicadores com os valores do array
    */
    function fillIndicadores(indicadores,multiselect)
    {
        var array;
        if(multiselect == true && (indicadores.length >0))
            array = adicionaOpcaoTodos(indicadores);
        else
            array = indicadores;
        var html = "";

        $.each(array,function(i,item)
        {
            if(multiselect == true)
            {
                var array = getArrayOfIndicadores(item.id);
                var classItem = ((array.length >=1 ) ? 'class="selected"' : '');

                html += "<li data-id='" + item.id + "' data-desc='" + item.desc +"' data-sigla='" + item.sigla + "' " + classItem +"><a title='" + item.desc + "'>" + item.nc + "</a></li>";
            }
            else        
                html += "<li data-id='" + item.id + "' data-desc='" + item.desc +"' data-sigla='" + item.sigla +"'><a>" + item.nc + "</a></li>";
        });
        $(this_selector_element).find('.indicador .nav').html(html);
        listenerClickIndicador(multiselect);
    }
    
    function getDivAno(arrayIndicadores, idIndicador)
    {
        if(multiYear == false || multiYear == undefined) return "";
        
        var classAno1 = "";
        var classAno2 = "";
        var classAno3 = "";
        

        $.each(arrayIndicadores, function(i,item)
        {
            if(item.a === 1)classAno1 = 'selected';
            if(item.a === 2)classAno2 = 'selected';
            if(item.a === 3)classAno3 = 'selected';
        });


        var html = "<div class='indicador_ano'>";
        html += "<span data-id=1 style='text-align:left;padding-left:8px;' data-indicador="+ idIndicador + " class='year1 " + classAno1 + "'>1991</span>";
        html += "<span data-id=2 style='text-align:center' data-indicador=" + idIndicador + " class='year2 " + classAno2 + "'>2000</span>";
        html += "<span data-id=3 style='text-align:right' data-indicador=" + idIndicador + " class='year3 " + classAno3 + "'>2010</span>";
        html += "</div>";

        return html;
    }

    /**
    * @param array - Recebe um array de valores, com propriedades nome e id
    * Preenche a lista de temas com os valores do array
    */
    function fillTemas(array)
    {
        var html = "";
        
//        if(array.length > 1)
//        {
//            var value = [];
//            value.n = "Todos";
//            value.nivel = "2";
//            value.id = '-1';
//            var newArray = [value];
//            array = newArray.concat(array);
//        }
       
        $.each(array,function(i,item)
        {
            var style = "";
            if(item.nivel == 3)
                style="style='padding-left: 20px;'";
            html += "<li data-id=" + item.id + "><a " + style + ">" + item.n + "</a></li>";
        });

        $(this_selector_element).find('.tema .nav').html(html); 
    }
    
    /**
    * @param multiselect - Informa se a lista Ã© de mÃºltiplca seleÃ§Ã£o ou de simples seleÃ§Ã£o
    * Habilita o evento de click na lista.
    */
    function listenerClickIndicador(multiselect)
    {
        if(multiselect == false)
        {
            $(this_selector_element).find('.indicador ul li').click(function(e){
                $(this_selector_element).find('.indicador ul li').removeClass('active');
                $(this).addClass('active');

                $(this_selector_element).find('div.divCallOut .popover').hide();

                var objeto = getIndicadorById(parseInt($(this).attr('data-id')));

                value_indicador[0] = objeto;
                
                fillLabelButtonIndicador();

                dispatchListener();
            });
        }
        else
        {
                $(this_selector_element).find('.indicador ul li a').click(function(e){

                if(parseInt($(this).parent().attr('data-id')) == -1)
                {
                    
                    var lengthAll = $(this_selector_element).find('.indicador ul li').length;
                    var lengthSelected = $(this_selector_element).find('.indicador ul li.selected').length;
                    var lengthUnSelected = lengthAll - lengthSelected - 1;
                    var length = value_indicador.length + lengthUnSelected;

                    //$(this_selector_element).find('.indicador ul li').addClass('selected');

                    $.each($(this_selector_element).find('.indicador ul li'), function(i,item)
                    {
                        var idSelecionado = parseInt($(this).attr('data-id'));
                        if(idSelecionado == -1)
                        {
                            $(this).removeClass('selected');
                        }
                        else
                        {
                            var ind = getIndicadorById(idSelecionado);
                            ind.a = 3;
                            adicionaIndicador(ind,$(this));
                        }
                    });
                    return;
                }

                var objeto = getIndicadorById(parseInt($(this).parent().attr('data-id')));

                if($(this).parent().hasClass('selected') == false)
                {
                    objeto.a = 3;
                    adicionaIndicador(objeto,$(this).parent());
                }
                else
                {
                    $(this).parent().removeClass('selected');
                    $(this).parent().find('.indicador_ano span').removeClass('selected');
                    
                    removeIndicadores(objeto);
                }
            });
        }
        
        $(this_selector_element).find(".indicador ul li a").tooltip({delay: 500});
    }
    
    function fillLabelButtonIndicador()
    {
        var objeto = value_indicador[0];
        textoIndicadorSelecionado = objeto.nc;
                
        if(textoIndicadorSelecionado.length > 8)
            textoIndicadorSelecionado = textoIndicadorSelecionado.slice(0,8) + '...';

        $(this_selector_element).find('.selector_popover').html(textoIndicadorSelecionado);
        $(this_selector_element).find('.selector_popover').prop('title',objeto.nc);
    }


    /**
    * @description Verifica se o indicador estÃ¡ no array de indicadores selecionados
    */
    function contains(value)
    {
        var retorno = false;
        
        for(var i = 0; i < value_indicador.length; i++)
        {
            if(value_indicador[i].id == value.id && value_indicador[i].a == value.a)
            {
                retorno = true;
                break;
            }
        } 

        return retorno;
    }

    function getPosition(value)
    {
        var retorno = -1;
        
        for(var i = 0; i < value_indicador.length; i++)
        {
            if(value_indicador[i].id == value.id)
            {
                retorno = i;
                break;
            }
        } 

        return retorno;
    }

    function getArrayOfIndicadores(idIndicador)
    {
        var array = new Array();

        for(var i = 0; i < value_indicador.length; i++)
        {
            if(parseInt(value_indicador[i].id) == parseInt(idIndicador))
                array.push(value_indicador[i]);
        } 

        return array;
    }

    /**
    * Adiciona o indicador a lista de indicadores selecionados.
     */
    function adicionaIndicador(value,ele)
    {
       
        
        
        $(this_selector_element).find('.messages').html("");   
        
        if(!skipLimit)
        {
            
            var idc = value_indicador.length + 1;
            var lug = geral.getTotalLugares();
            var produto =  lug * idc;

            if(produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Seleção atual: Indicadores ('+ idc +'), Lugares ('+ lug +'), Células('+ produto +'). A tabela será disponibilizada para download.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
            }
            else if(produto >= JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Atenção: sua consulta superou o limite de ('+ JS_LIMITE_DOWN +') células na tabela. <br/>Acesse "Download" e baixe todos os dados do Atlas Brasil 2013.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message); 
                return 0;
            }       
        }
        
        
        
        if(contains(value) == false)
        {
            ele.addClass('selected');
            
            if(value_indicador.length == 0)
                value.c = true;
            
            var posicao = getPosition(value);
            
            if(posicao != -1)
                value_indicador.splice(posicao,0,value);
            else
                value_indicador.push(value);

            fillSelectedItens();
        }

    }

    /**
    * Remove um indicador da lista
    */
    function removeIndicador(value)
    {

        for(var i = 0; i < value_indicador.length; i++)
        {
            if(parseInt(value_indicador[i].id) == parseInt(value.id) && value_indicador[i].a == value.a)
            {
                value_indicador.splice(i,1);
            }
        }
        fillSelectedItens();
        
        $(this_selector_element).find('.messages').html("");   
        
        if(!skipLimit)
        {
            
            var idc = value_indicador.length;
            var lug = geral.getTotalLugares();
            var produto =  lug * idc;


            if(produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Seleção atual: Indicadores ('+ idc +'), Lugares ('+ lug +'), Células('+ produto +'). A tabela será disponibilizada para download.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
            }
            else if(produto >= JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Atenção: sua consulta superou o limite de ('+ JS_LIMITE_DOWN +') células na tabela. <br/>Acesse "Download" e baixe todos os dados do Atlas Brasil 2013.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message); 
                return 0;
            }       
        }
        
    }

    /**
    * Remove todas as ocorrÃªncias de um indicador
    */
    function removeIndicadores(value)
    {

        var tmp_array = new Array();
        for(var i = 0; i < value_indicador.length; i++)
        {
            if(parseInt(value_indicador[i].id) != parseInt(value.id))
            {
                tmp_array.push(value_indicador[i]);
            }
        }
        value_indicador = tmp_array;
        
        fillSelectedItens();
        
        $(this_selector_element).find('.messages').html("");   
        
        if(!skipLimit)
        {
            
            var idc = value_indicador.length;
            var lug = geral.getTotalLugares();
            var produto =  lug * idc;
            
            if(produto >= JS_LIMITE_TELA && produto < JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Seleção atual: Indicadores ('+ idc +'), Lugares ('+ lug +'), Células('+ produto +'). A tabela será disponibilizada para download.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message);
            }
            else if(produto >= JS_LIMITE_DOWN)
            {
                var message = '<div class="alert">';
                message += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
                message += 'Atenção: sua consulta superou o limite de ('+ JS_LIMITE_DOWN +') células na tabela. <br/>Acesse "Download" e baixe todos os dados do Atlas Brasil 2013.';
                message += '</div>';
                $(this_selector_element).find('.messages').html(message); 
                return 0;
            }       
        }
        
    }

    /**
    * @description Pega o objeto da lista de indicadores a partir da sigla
    */
    function getIndicadorById(value)
    {
        var length = array_indicadores.length;
        for(var i = 0; i < length; i++)
        {
            var item = array_indicadores[i];
            if(parseInt(item.id) == parseInt(value))
            {
                var objeto = new IndicadorPorAno();
                objeto.id = item.id;
                objeto.c = item.c;
                objeto.a = item.a;
                objeto.desc = item.desc;
                objeto.nc = item.nc;
                
                return objeto;
            }
        }
    }

    function convertToArray(value)
    {
        if($.isArray(value))
            return value;
        else
            return [value]; 
    }

    function refresh()
    {
        value_indicador = geral.getIndicadores().slice();
        fillSelectedItens();
    }

    /*function setIndicadoresValue(array_values)
    {
        value_indicador = array_values;
        dispatchListener();
    }**/
    
    function getIndicadoresDistintos(array)
    {
        var novosIndicadores = new Array();

        for(var i = 0; i < array.length; i++)
        {
            var item = array[i];
            
            if(containsInArray(novosIndicadores,item) == false)
            {
                novosIndicadores.push(item);
            }
        }
        return novosIndicadores;
    }

    function containsInArray(array,value)
    {
        for(var i = 0; i < array.length; i++)
        {
            if(array[i].id == value.id)
                return true;
        } 
        return false;
    }
    
    function closePopOver()
    {
        $('html').on('click.popover.divCallOut',function(e) 
        {
            if($(e.target).has('.divCallOut').length == 1)
            {
                $(this_selector_element).find('.divCallOut .popover').hide();
                
                value_indicador = value_indicador_old.slice();
            }
        });
    }

}