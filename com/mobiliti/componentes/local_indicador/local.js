var dataLocal = null;


function SeletorIndicador()
{
	var itens = new Array();
	var listener;
	var element_context;
	var lazy_select = false;
    var lazy_array;
    var multiSelecao;
    var espacialidade_selecionada = 2;

    this.setButton = function(html)
   	{
   		$(element_context).find('.button').html(html);
   	}

    function dispatcher(array)
    {
		listener(itens);
    }

	this.startLocal = function(listener_value,context,_multiSelecao)
	{
		multiSelecao = _multiSelecao;

		listener = listener_value;

		element_context = '#' + context;

		enableBtnCleanAll();
		enabledBtnAll();
		// enabledSearch();
	}
	
	function enableBtnCleanAll()
	{
		$(element_context).find('.btn_clean_local').removeAttr("disabled");
		$(element_context).find('.btn_clean_local').click(function()
		{
			$.each(itens,function(i,item)
			{
				$.each(item.l,function(k,local)
				{
					local.c = false;
				});
			});
			$(element_context).find('.list_local li.selected').removeClass('selected');
			dispatcher(itens);
		});
	}

	function enabledBtnAll()
	{
		$(element_context).find('.btn_all').removeAttr("disabled");
		$(element_context).find('.btn_all').click(function()
		{
			$.each(itens,function(i,item)
			{
				$.each(item.l,function(k,local){
					local.c = true;
				});
			});

			$(element_context).find('.list_local li').addClass('selected');
			
			dispatcher(itens);
		});
	}

	function enabledSelected()
	{
		$(element_context).find('.list_local li').click(function()
		{
			var objeto = {};
			objeto.id = parseInt($(this).attr('data-id'));
			objeto.nome = padronizaNome($(this).text());

	        if($(this).hasClass('selected') == false)
	        {
	            var obj = getElement(objeto);
	            obj.c = true;
	            $(this).addClass("selected");

	            dispatcher(itens);
	        }
	        else
	        {
	            var obj = getElement(objeto);
	            obj.c = false;
	            $(this).removeClass('selected');

	            dispatcher(itens);
	        }
		});
	}

	function preenche(array)
	{
		enabledSelected();
		var html = "";
		
		$.each(array,function(i,item)
		{
			checked = ((item.c == true) ? 'class="selected"':'');
			html += "<li data-id='" + item.id + "' " + checked +"><div class='icon_select'></div><div class='icon_remove'></div>" + item.nc + "</li>";
		});
		
		$(element_context).find('.list_local_indicadores').html(html);
		
		$(element_context).find('.local li').click(function(){
			$(element_context).find('.icon_select').removeClass('selected');

			$(this).addClass('selected');
			var id = parseInt($(this).attr('data-id'));

			$.each(itens,function(i,item)
	    	{
	    		if(item.id == id)
	    			item.c = true;
	    		else
	    			item.c = false;
	    	});
	    	
			dispatcher(itens);
		});


		$(element_context).find('.icon_select').click(function(){
			$(element_context).find('.icon_select').removeClass('selected');

			$(this).parent().addClass('selected');
			var id = parseInt($(this).parent().attr('data-id'));

			$.each(itens,function(i,item)
	    	{
	    		if(item.id == id)
	    			item.c = true;
	    		else
	    			item.c = false;
	    	});
	    	
			dispatcher(itens);
		});

		$(element_context).find('.icon_remove').click(function()
		{
			var objeto = {};
			objeto.id = parseInt($(this).parent().attr('data-id'));

            $(this).parent().removeClass('selected');
			removeElement(objeto);
			rechecked();
            dispatcher(itens);
		});
	}
	
	function rechecked()
	{
		var hasChecked = false;

		for(var i=0;i<itens.length;i++)
		{
			if(itens[i].c === true) 
				hasChecked = true;
		}
		if(hasChecked === false)
		{
			for(var i = 0; i < itens.length; i++)
			{
				if(i == 0)
					itens[i].c = true;
			}
		}
	}

	function enabledSearch()
	{
		$(element_context).find('#inputSearchLocal').removeAttr("disabled");

		$(element_context).find('#inputSearchLocal').keyup(function() 
		{
			  if($(element_context).find('#inputSearchLocal').val().length > 3)
			  {
				  search($(element_context).find('#inputSearchLocal').val());
			  }
			  else
			  {
				  preenche();
			  }
		});
	}
	function search(text)
	{
		// preenche(array)
	}

	this.refresh = function()
	{
		itens = geral.getIndicadores();
		
		preenche(itens);
	}

    this.getItens = function()
    {
    	return itens;
    }

	this.setItens = function(value)
    {
    	setItensValue(value);
    }

    function setItensValue(value)
    {
    	$.each(value,function(i,item)
    	{
    		item.a = 2;
    		if(i == 0)
				item.c = true;
			else
				item.c = false;
    	});

    	itens = value;
    	preenche(itens);
    	dispatcher(itens);
    }

    function removeElement(value)
	{
		var lista = itens;

	    for(var i = 0; i < lista.length; i++)
	    {
	    	if(parseInt(lista[i].id) == parseInt(value.id))
	    	{
	    		lista.splice(i,1);
	    	}
	    } 
	}
}