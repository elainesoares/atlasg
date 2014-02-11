var dataLocal = null;


function SeletorIndicadorG()
{
//    console.log('SELECTORINDICADOR')
    var itens = new Array();
    var listener;
    var element_context;
    var lazy_select = false;
    var lazy_array;
    var     multiSelecao;
    var espacialidade_selecionada = 2;
    var eixo = new Array();
    eixo['y'] = false;
    eixo['x'] = false;
    eixo['tam'] = false;
    eixo['cor'] = false;

    this.setButton = function(html){
//        console.log('setButton');
        $(element_context).find('.button').html(html);
    }

    function dispatcher(array){
//        console.log('dispatcher');
        listener(itens);
    }
    
    this.getEixo = function(){
        return eixo;
    }

    this.startLocal = function(listener_value,context,_multiSelecao, eixo_){
//        console.log('startLocal');
        eixo['eixo'] = eixo_;
        multiSelecao = _multiSelecao;
        
        listener = listener_value;
        
        element_context = '#' + context;
        enableBtnCleanAll();
        enabledBtnAll();
    }
	
    function enableBtnCleanAll(){
//        console.log('enableBtnCleanAll');
        $(element_context).find('.btn_clean_local').removeAttr("disabled");
        $(element_context).find('.btn_clean_local').click(function(){
            $.each(itens,function(i,item){
                $.each(item.l,function(k,local){
                    local.c = false;
                });
            });
            $(element_context).find('.list_local li.selected').removeClass('selected');
            dispatcher(itens);
        });
    }

    function enabledBtnAll()
    {
//        console.log('enabledBtnAll');
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
//            console.log('objeto.id: '+objeto.id);
            objeto.nome = padronizaNome($(this).text());
//            console.log('objeto.nome: '+objeto.nome);
            var obj = getElement(objeto);
            obj.c = true;
            $(this).addClass("selected");
            dispatcher(itens);
        });
    }

    function preenche(array)
    {
//        console.log('PREENCHE');
        enabledSelected();
        var html = "";
        $.each(array,function(i,item)
        {
            checked = ((item.c == true) ? 'class="selected"':'');
            if(eixo['eixo'] == true){
//                console.log('2');
                html += "<li data-id='" + item.id + "' " + checked +"><div class='icon_select'></div><div class='icon_remove'></div>" + item.nc + "</li>";
            }
            else{
//                console.log('1');
                checked = 'class=selected';
                html += "<li data-id='" + item.id + "' " + checked +" style='margin-top: 10px; margin-left: -20px;'></div>" + item.nc + "</li>";
            }
            
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
    }

    this.refresh = function()
    {
//        console.log('refresh2');
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
