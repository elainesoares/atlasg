var dataLocal = null;

function SeletorLocal()
{
	var itens_selecionados = new Array();
	var listener;
	var element_context;
	var lazy_select = false;
        var lazy_array;
        var mult_espacializacao;
        var espacialidade_selecionada = 0;

    
        //adicionando por reinaldo
        var listenerSelectionItem = null;
        this.setListenerSelectionItem = function(_listener)
        {
            listenerSelectionItem = _listener;
        }
    
        this.setButton = function(html)
        {
           $(element_context).find('.button').html(html);
        }

        this.getEspacialidade_selecionada = function()
        {
            return espacialidade_selecionada;
        }

        this.selectedElement = function(value)
        {
            try
            {
                var string_id = '[data-id=' + value + ']';
                var $element = $(element_context).find('.local .title_toggle').next().find(string_id);

                $(element_context).find('.local li').removeClass('selection');
                $element.addClass('selection');
                var top = 438 - $(element_context).find('.local').scrollTop();

                value = $element.offset().top - top;
                $(element_context).find('.local').animate({scrollTop:value}, 'slow');
            
            }
            catch(err)
            { }
        }

        function dispatcher(array)
        {
            
            $.each(itens_selecionados,function(i,item)
            {
                    if(item.e == espacialidade_selecionada)
                        item.ac = true;
                    else
                        item.ac = false;

            });
           
            listener(itens_selecionados);
        }

	this.startLocal = function(listener_value,context,multipla_espacializacao)
	{
		listener = listener_value;
		element_context = '#' + context;
		mult_espacializacao = multipla_espacializacao;
		

//                $(element_context).find('.local').scroll(function (e){
//                    
//                    console.log($(e.target).height() + " -> " + $(e.target).scrollTop());
//                });

		$(element_context).find('.title_toggle').click(function()
		{
                       
                        
			espacialidade_selecionada = parseInt($(this).attr("data-espacialidade"));
			
			$(element_context).find('.title_toggle').removeClass('selected_item');
			$(element_context).find('.list_local').fadeOut();

			$(element_context).find('.inputSearchLocal').val('');

			$(this).toggleClass('selected_item');
			$(this).next().fadeIn();

			if(mult_espacializacao == false)
			{
				dispatcher(itens_selecionados);
			}
                        
                        
                       
		});

		enableBtnCleanAll();
		enabledBtnAll();
		// enabledSearch();
	}

	function addEspacialidade(array,espacialidade)
	{
		$.each(array,function(i,item)
		{
			item.e = espacialidade;
		});
	}

	function enableBtnCleanAll()
	{
		$(element_context).find('.btn_clean_local').removeAttr("disabled");
		$(element_context).find('.btn_clean_local').click(function()
		{
			$.each(itens_selecionados,function(i,item)
			{
				$.each(item.l,function(k,local)
				{
					local.c = false;
					local.s = false;
				});
			});
			$(element_context).find('.list_local li.selected').removeClass('selected');
			dispatcher(itens_selecionados);
		});
	}

	function enabledBtnAll()
	{
		$(element_context).find('.btn_all').removeAttr("disabled");
		$(element_context).find('.btn_all').click(function()
		{
			$.each(itens_selecionados,function(i,item)
			{
				$.each(item.l,function(k,local){
					local.c = true;
					local.s = false;
				});
			});

			$(element_context).find('.list_local li').addClass('selected');
			
			dispatcher(itens_selecionados);
		});
	}

	function setEspacialidadePorDadosPreenchidos()
	{
            var countMunicipal;
            var countEstadual;
            var countAreaTematica;

            lista = itens_selecionados;

	    for(var i = 0; i < lista.length; i++)
	    {
	    	var locais = lista[i].l;
	    	if(lista[i].e == 2)
                    countMunicipal = locais.length;
	    	else if(lista[i].e == 4)
                    countEstadual = locais.length;
                else if(lista[i].e == 7)
                    countAreaTematica = locais.length;
	    } 
            
            
            if(espacialidade_selecionada == 0)
            {    
                if(countAreaTematica > 0)
                    espacialidade_selecionada = 7;
                else if(countMunicipal > 0)
                    espacialidade_selecionada = 2;
                else if(countEstadual > 0)
                    espacialidade_selecionada = 4;
            }
            
            var string = "";
            $.each([2, 4, 7], function(index, value) 
            {    
                string = '.title_toggle[data-espacialidade='+ value + ']';
                if(espacialidade_selecionada != value)
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

	this.refresh = function()
	{
            itens_selecionados = geral.getLugares();
            setEspacialidadePorDadosPreenchidos();
            preencheLista();
	}

	function preencheLista()
	{
          
         
            //$(element_context).find('.list_local_cidades').html("");
            $("#head_list_c").hide();
            var display_cidade = false;
            
	    //$(element_context).find('.list_local_estados').html("");
            $("#head_list_e").hide();
            var display_estado = false;
            
            //$(element_context).find('.list_local_areas_tematicas').html("");
            $("#head_list_at").hide();
            var display_tematica = false;
            
	    
            $.each(itens_selecionados,function(i,item)
            {
                    
                    if(item.e == 2)
                    {
                         preencheCidades(item.l);
                         if( item.l.length != 0 && item.l != undefined && item.l != null) display_cidade = true;
                    }
                    else if(item.e == 4)
                    {
                         preencheEstados(item.l);
                         if( item.l.length != 0 && item.l != undefined && item.l != null)  display_estado = true;
                    }
                    else if(item.e == 7)
                    {
                         preencheAreasTematicas(item.l);
                         if( item.l.length != 0 && item.l != undefined && item.l != null) display_tematica = true;
                    }
            });
            
            
            if(display_cidade)$("#head_list_c").show();
            if(display_estado)$("#head_list_e").show();
            if(display_tematica)$("#head_list_at").show();
            
            
            enabledSelected();
	}

	function enabledSelected()
	{
		$(element_context).find('.list_local li').click(function()
		{
                     
			$(element_context).find('.list_local li').removeClass('selection');
			$(this).addClass('selection');

			var objeto = {};
			objeto.id = parseInt($(this).attr('data-id'));
			objeto.e = parseInt($(this).attr('data-espacialidade'));
			objeto.nome = $(this).text();
                         
                        //Pra ficar mais rápido chama o listener diretamente    
                        if(listenerSelectionItem == null)
                            dispatcher(itens_selecionados);
                        else
                            listenerSelectionItem(objeto.id,objeto.e);
		});

		$(element_context).find('.icon_select').click(function()
		{
			var objeto = {};
			objeto.id = parseInt($(this).parent().attr('data-id'));
			objeto.e = $(this).parent().attr('data-espacialidade');
                        espacialidade_selecionada = objeto.e;
			objeto.nome = $(this).parent().text();
			
                        if($(this).parent().hasClass('selected') == false)
                        {
                            var obj = getElement(objeto);
                            obj.c = true;
                            $(this).parent().addClass("selected");

                            dispatcher(itens_selecionados);
                        }
                        else
                        {
                            var obj = getElement(objeto);
                            obj.c = false;
                            $(this).parent().removeClass('selected');

                            dispatcher(itens_selecionados);
                        }
		});

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
    
        function classLi(item)
        {
            if(item.c == true && item.s == true)
                    return "class='selected selection'";
            else if(item.c == true)
                            return "class='selected'";
                    else if(item.s == true)
                            return "class='selection'";
                    return "";
        }

	function preencheCidades(cidades)
	{
		var html = "";
                                
                for (var i=0, il=cidades.length; i<il; i++) 
                {
                    var item = cidades[i];
                    var classSelected = classLi(item);
                 
                    
                    html += '<li data-espacialidade=2 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>'  + item.n + '</li>' 
                }

		$(element_context).find('.list_local_cidades').html(html);
	}
        
        
        function preencheAreasTematicas(areasTematicas)
	{
		var html = "";

		$.each(areasTematicas,function(i,item)
		{
			var classSelected = classLi(item);

			html += '<li data-espacialidade=7 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>' + item.n + '</li>';
		});

		$(element_context).find('.list_local_areas_tematicas').html(html);
	}

	function preencheEstados(estados)
	{
		var html = "";

		$.each(estados,function(i,item)
		{
			var classSelected = classLi(item);

			html += '<li data-espacialidade=4 data-id=' + item.id + ' ' + classSelected + '><div class="icon_select"></div><div class="icon_remove"></div>' + item.n + '</li>';
		});

		$(element_context).find('.list_local_estados').html(html);
	}

	/**
	* @description
	*/
	function getElement(value)
	{
		lista = itens_selecionados;

	    for(var i = 0; i < lista.length; i++)
	    {
	    	if(parseInt(lista[i].e) == parseInt(value.e))
	    	{
	    		var locais = lista[i].l;
	    		
	    		for(var k = 0; k < locais.length; k++)
	    		{
	    			if(locais[k].id == value.id)
	    				return locais[k];
	    		}
	    	}
	    } 
	}

	/**
	* @description 
	*/
	function removeElement(value)
	{
		lista = itens_selecionados;

	    for(var i = 0; i < lista.length; i++)
	    {
	    	if(parseInt(lista[i].e) == parseInt(value.e))
	    	{
	    		var locais = lista[i].l;
	    		
	    		for(var k = 0; k < locais.length; k++)
	    		{
	    			if(locais[k].id == value.id)
	    			{
	    				locais.splice(k,1);
	    			}
	    		}
	    	}
	    } 
	}
        
	/**
	* @description Pega o objeto da lista pelo name
	*/
	function getItemByName(value)
	{
	    var cidades = dataLocal.cidades;
	    var estados = dataLocal.estados;
	    
	    for(var i = 0; i < cidades.length; i++)
	    {
	        var item = cidades[i];
	        if(padronizaNome(item.nome) == value.nome && item.e == value.e)
	            return item;
	    }

	    for(var i = 0; i < estados.length; i++)
	    {
	        var item = estados[i];
	        if(padronizaNome(item.nome) == value && item.e == value.e)
	            return item;
	    }
	}

	function convertToArray(value)
        {
            if($.isArray(value))
                return value;
            else
                return [value]; 
        }

            this.setItensSelecionados = function(array_values)
        {
            setItens(array_values);
        }

        this.getItensSelecionados = function()
        {
            return itens_selecionados;
        }

        function setItens(array_values)
        {
            itens_selecionados = array_values;
            setEspacialidadePorDadosPreenchidos();
            preencheLista();
            dispatcher(itens_selecionados);
        }

}