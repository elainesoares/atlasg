/* 
 * class: Selector
 * Esta classe serve como um seletor genérico para locais e 
 * indicadores.
 */

function SelectorIndicator(selectorArea,listArea,inputSearch,selectionListener,changeListListener,multiSelect,options){
    
    //------------------------------------------------------------
    // Força a criação do método filter caso não exista.
    //------------------------------------------------------------
    if (!Array.prototype.filter){
      Array.prototype.filter = function(fun /*, thisp*/){
        var len = this.length;
        if (typeof fun !== "function")
          throw new TypeError();

        var res = new Array();
        var thisp = arguments[1];
        for (var i = 0; i < len; i++){
          if (i in this){
            var val = this[i]; // in case fun mutates this
            if (fun.call(thisp, val, i, this))
              res.push(val);
          }
        }

        return res;
      };
    }
     //------------------------------------------------------------
    
    
    var _checked_itens = new Array();
    var _intern_list = null;
    
    
    //public attributes
    var _selectorArea = selectorArea;
    var _listArea = listArea;
    
    // busca na lista interna
    var _typingTimer;                //timer identifier
    var _doneTypingInterval = 1000;  //time in ms, 5 second for example
    var _inputSearch = inputSearch;
    
    // evento de seleção na lista interna
    var _selectionListener = selectionListener;
    //evento de mudança da lista interna
    var _changeListListener = changeListListener;
    
    var _multiSelect = multiSelect;
    var _options = options;
    

     _construct_selector(this);
     try{
        var map_indc_selector = new IndicatorSelector();
        map_indc_selector.startSelector(true, _selectorArea, _choice_ready ,"right");
     }catch(e){
         //erro vazio
     }
     
     
    function _construct_selector(){ 
        
        var list = $("#" + _listArea);
        list.css("overflow-y","scroll");
        list.css("height","250px");
        list.css("margin-right","15px");
        list.css("font-family","Arial, Verdana, 'Times New Roman'");
        list.css("font-size","14px");
        
           
        var ui = $("#" + _selectorArea);
        ui.html("<span id='indc_text'>INDICADORES</span> <div class='divCallOut'> <button id='obj_selector' class='btn btn-primary dropdown selector_popover' style='float:right; margin-right:10px;' data-toggle='dropdown' rel='popover'><a>Selecionar</a></button></div>");
        
        
        $("#indc_text").css("color","black");
        $("#indc_text").css("position","relative");
        $("#indc_text").css("top","25px");
        $("#indc_text").css("left","30px");
        $("#indc_text").css("font-family","Arial, Verdana, 'Times New Roman'");
        $("#indc_text").css("font-weight","bold");
        
        //--------------------------

        var search = $("#" + _inputSearch);
        search.html("<input id='input_search_intern_list' style='background: url(img/icons/lupa.png) no-repeat scroll 97% 7px; padding-right:25px;' type='text' size='50' value=''></input>");
        
        $("#input_search_intern_list").css("position","relative");
        $("#input_search_intern_list").css("left","10px");

        
        $("#input_search_intern_list").keyup(function(){
            _typingTimer = setTimeout(_done_type_end_evt, _doneTypingInterval);
        });
        
        $('#input_search_intern_list').keydown(function(){
            clearTimeout(_typingTimer);
        });
    }
    
    //executa a busca no array interno de indicadores
    function _done_type_end_evt(){

        var filter = $('#input_search_intern_list').val();
       
        if(_intern_list === null)return;

        if(filter === ""){
            _fill_with_filtered_list(_intern_list);
            return;
        };
        
        
        var filtered  = _intern_list.filter(function(element, index, array){
            var nome = element.nome;
            if(nome.indexOf(filter) !== -1)return element;

        });
            
        _fill_with_filtered_list(filtered);
        
    }
    
  
    
    
    function _choice_ready(arr)
    {
        
        _checked_itens = new Array();

        var ui = $("#" + _listArea);

        var length = arr.length,
        element = null;

        opt_html = "";

        for (var i = 0; i < length; i++){    
              element = arr[i];
              opt_html = opt_html + "<li class='ipt_li_input' id='ipt_li_input_" + i + "'><input value='{\"id\":\"" + element.id  + "\",\"sigla\":\"" +  element.sigla  + "\"}'  class='ipt_selector_checkbox' type='hidden' />" + element.nome + "</li>";
            }

            ui.html("<ul id='lista_de_itens_local' >" + opt_html + "</ul>");      
            $(".ipt_li_input").css("list-style-type","none");


            if(_options !== null)
            {
              for (var i = 0; i < length; i++) 
              {
                 element = arr[i];  
                _create_popover_for($("#ipt_li_input_" + i ),_options,element.id,element.sigla);
              }
            }

            $(ui).find('.ipt_li_input').click(function (e){ _on_click_evt(e) } );

            $("#input_search_intern_list").val("");



            _intern_list = arr;
            _changeListListener(arr);
    }
    
    
    function _fill_with_filtered_list(list)
    {
       
        
       var ui = $("#" + _listArea);
              
       var length = list.length;
       element = null;

       opt_html = "";

       for (var i = 0; i < length; i++) 
       {    
         element = list[i];
         opt_html = opt_html + "<li class='ipt_li_input' id='ipt_li_input_" + i + "'><input value='{\"id\":\"" + element.id  + "\",\"sigla\":\"" +  element.sigla  + "\"}'  class='ipt_selector_checkbox' type='hidden' />" + element.nome + "</li>";
       }
       
       ui.html("<ul>" + opt_html + "</ul>");      
       $(".ipt_li_input").css("list-style-type","none");
       
       
       // verifica se é necesário remarcar
//       (_checked_itens);
//       for (var i = 0; i < _checked_itens; i++) 
//       {
//         cosole.log($("#" + _listArea ));  
//           
//         $("#" + _listArea  +" li").each(function() 
//         {
//            var chk = $(this).children();
//            var obj = $.parseJSON(chk.val());
//            
//             
//              
//         });
//       }
       


       if(_options !== null)
       {
         for (var i = 0; i < length; i++) 
         {
            element = list[i];  
           _create_popover_for($("#ipt_li_input_" + i ),_options,element.id,element.sigla);
         }
       }
       
       $(ui).find('.ipt_li_input').click(function (e){ _on_click_evt(e) } );
             
    }
    
    
    
    
    function  _on_click_evt(e)
    {
        
        _element = $(e.currentTarget);
   
        var attr_id = $(_element).attr('id');

        var cont = 0;
        //se for necessário desmarcar
         $("#" + _listArea  +" li").each(function() 
         {
               
               if( $(this).attr('id') !== _element.attr('id') )
               {    
                   if(!_multiSelect)
                   {
                       $("#ipt_li_input_" + cont).css("list-style-image","url('img/selector/white.jpg')");
                   }
                   
                   $("#ipt_li_input_" + cont).popover('hide'); 
               }
               
               if(!_multiSelect)
               {
                   if( $(this).attr('id') !== _element.attr('id') ){ $(this).prop('checked', false); };
               }
               
               cont++;
         });
         
         
        
        
        if(_options !== null)  //se houver options
        {
            $("#" + attr_id).popover('show');
            $(document).find('._intern_option').click(function (e){ _option_clk_evt(e) } );   
        }
        else //se não houver options disparar evento logo
        {
            
            $("#" + attr_id).css("list-style-image","url('img/selector/check.jpg')");
            var ipt = _element.children();
            
            var obj = $.parseJSON(ipt.val());
            _checked_itens.push(obj.sigla);
            
            _option_direct_clk_evt(ipt);
        }
        
        
    }
    
    
    function  _create_popover_for(e,opts,id,sigla)
    {
        
        e.popover({
                    trigger:'manual',
                    html:true,
                    animation: true,
                    delay: { show: 1500, hide: 1500 },
		    placement: 'right',
		    content: function() 
                    {
                        var html ="";
                        
                        for (var i = 0; i < opts.length; i++) 
                        {    
                            html = html + "<span class=\'_intern_option\'>" + opts[i] + 
                                    "<input class='option_id' type='hidden' value='" + id + "' />" + 
                                    "<input class='option_sigla' type='hidden' value='" + sigla + "' />" +
                                    "<input class='option_name' type='hidden' value='" + opts[i] + "' />" +
                                    "<input class='option_object_to_hide' type='hidden' value='" + e.prop("id") + "' />" 
                                    + "</span></br>";

                        }
                        
                        return html; 
                    }    
                });                
    }
    
    //clique em alguma das opções
    function _option_clk_evt(e)
    {
        _element = $(e.currentTarget);
        var _hide_popover = "";
        
        var obj = $.parseJSON('{"id":"","sigla":"","option":""}');
        
        _element.children('input').each(function () {
            
            if($(this).prop("class")==="option_id")
            {
                obj.id = this.value;
            }
            else if($(this).prop("class")==="option_sigla")
            {
                obj.sigla = this.value;
            }
            else if($(this).prop("class")==="option_name")
            {
                obj.option = this.value;
            }   
            else if($(this).prop("class")==="option_object_to_hide")
            {
                _hide_popover = this.value;
            }
        });
        
        $("#" + _hide_popover).css("list-style-image","url('img/selector/check.jpg')");
        $("#" + _hide_popover).popover('hide'); 
        _selectionListener(obj);
        
    }
    
    //clique direto no indicador
    function _option_direct_clk_evt(obj)
    {
       var obj = $.parseJSON(obj.val());
       //_selectionListener(obj);
       //geral.getIndicadores();
       
    }
    
}

