var dataLocal = null;


function SeletorIndicadorGraficos()
{
    var itens = new Array();
    var listener;
    var element_context;
    var lazy_select = false;
    var lazy_array;
    var     multiSelecao;
    var espacialidade_selecionada = 2;

    //Escreve o botão
    this.setButton = function(html){
//        console.log('5 - setButton');
//        console.log(html);
        $(element_context).find('.button').html(html);      //Dentro da classe button que fica dentro de element_context escreve o conteúdo de html
    }

    function dispatcher(array){
//        console.log('dispatcher');
        listener(itens);
    }

    this.startLocal = function(listener_value,context,_multiSelecao){
//        console.log('1 - startLocal');
        multiSelecao = _multiSelecao;
        
        listener = listener_value;
        
        element_context = '#' + context;
        enableBtnCleanAll();
        enabledBtnAll();
    }
	
    function enableBtnCleanAll(){
//        console.log('2 - enableBtnCleanAll');
        $(element_context).find('.btn_clean_local').removeAttr("disabled");
        $(element_context).find('.btn_clean_local').click(function(){
            $.each(itens,function(i,item){
                $.each(item.l,function(k,local){
                    local.c = false;
                });
            });
            $(element_context).find('.list_local li.selected').removeClass('selected');     //Remove todas as classes 'selected' dentro de element_context
            dispatcher(itens);
        });
    }

    function enabledBtnAll()
    {
//        console.log('3 - enabledBtnAll');
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

    function enabledSelected(){
//        console.log('enabledSelected');
        $(element_context).find('.list_local li').click(function(){
            var objeto = {};
            objeto.id = parseInt($(this).attr('data-id'));
            objeto.nome = padronizaNome($(this).text());

            //	if($(this).hasClass('selected') == false){
            var obj = getElement(objeto);
            obj.c = true;
            $(this).addClass("selected");
            dispatcher(itens);
        //            }
        //            else{
        //                var obj = getElement(objeto);
        //	    obj.c = false;
        //	    $(this).removeClass('selected');
        //                dispatcher(itens);
        //            }
        });
    }

    // Preenche a lista de indicadores selecionados
    function preenche(array)
    {
//        console.log('preenche');
        enabledSelected();
        var html = "";
		
        $.each(array,function(i,item)
        {
            checked = ((item.c == true) ? 'class="selected"':''); //Se item.c for igual a true adiciona a class selected
            html += "<li data-id='" + item.id + "' " + checked +"><div class='icon_select'></div><div class='icon_remove'></div>" + item.nc + "</li>";      //Indicador selecionado
        });
		
        $(element_context).find('.list_local_indicadores').html(html);  //Dentro de list_local_indicadores escreve o conteúdo da variável html, que são os indicadores selecionados 
		
        $(element_context).find('.local li').click(function(){      //quando clicar em um dos indicadores selecionados
            $(element_context).find('.icon_select').removeClass('selected');    //do icon_select remove a classe selected

            $(this).addClass('selected');       //Adiciona a classe selected onde foi clicado
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


        $(element_context).find('.icon_select').click(function(){       //Se clicar em um item selecionado
            $(element_context).find('.icon_select').removeClass('selected');    //Remove a classe selected

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

        $(element_context).find('.icon_remove').click(function()        //Se for clicado no icone de remoção 'icon_remove' 
        {
            var objeto = {};
            objeto.id = parseInt($(this).parent().attr('data-id'));

            $(this).parent().removeClass('selected');   //Remove a classe selected
            removeElement(objeto);      //E chama a função para remover o elemento
            rechecked();
            dispatcher(itens);
        });
    }
	
    function rechecked()
    {
//        console.log('rechecked');
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
//        console.log('enabledSearch');
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
//        console.log('refresh3');
        itens = geral.getIndicadores();
        preenche(itens);
    }

    this.getItens = function()
    {
//        console.log('getItens');
        return itens;
    }

    this.setItens = function(value)
    {
//        console.log('setItens');
        setItensValue(value);
    }

    function setItensValue(value)
    {
//        console.log('setItensValue');
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
//        console.log('removeElement');
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