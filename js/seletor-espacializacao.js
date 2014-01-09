/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    $.extend($.fn, {
      selectorEspacializacao: function() {
        var params, publicMethods, settingsOrMethod;
        settingsOrMethod = arguments[0], params = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
        publicMethods = ["setRatio", "setValue"];
        return $(this).each(function() {
          var obj, settings;
          if (settingsOrMethod && __indexOf.call(publicMethods, settingsOrMethod) >= 0) {
            obj = $(this).data("slider-object");
            return obj[settingsOrMethod].apply(obj, params);
          } else {
            settings = settingsOrMethod;
            return $(this).data("slider-object", new SelectorEspacializacao($(this), settings));
          }
        });
      }
    });
    
    function SelectorEspacializacaoCheckEsp(val){
        str = "";
        switch(val){
            case 0:
                str = 'Espacialização';
                break;
            case 2:
                str = 'Municípal';
                break;
            case 3:
                str = 'Regional';
                break;
            case 4:
                str = 'Estadual';
                break;
            case 5:
                str = 'UDH';
                break;
            case 6:
                str = 'Região Metropolitana';
                break;
            case 7:
                str = 'Região de Interesse';
                break;
            case 8:
                str = 'Mesorregião';
                break;
            case 9:
                str = 'Microrregião';
                break;
            case 10:
                str = 'País';
                break;
            default:
                str = 'Espacialização';
                break;
        }
        return str;
    }
    function SelectorEspacializacao(input,settings)
    {
        if(typeof (settings) == 'object'){
            if(typeof settings.setVal !== 'undefined'){
                $("#"+input.attr("id")+"_inpSelectorEsp").attr("val",settings.setVal);
                $("#"+input.attr("id")+"_spanInpSelectorEsp").html(SelectorEspacializacaoCheckEsp(settings.setVal));
                input.attr("value",settings.setVal);
            }
        }
        else
            SelectorEspacializacaoBuild(input);
    }
    
    function SelectorEspacializacaoBuild(input)
    {
        strId = input.attr("id");
        input.css('display',"none");
        input.after('<div class="btn-group">'+
                        '<a class="btn dropdown-toggle" val="0" data-toggle="dropdown" id="'+strId+'_inpSelectorEsp">'+
                          '<div id="'+strId+'_spanInpSelectorEspDiv"><span id="'+strId+'_spanInpSelectorEsp">Espacialização</span></div>'+
                          ' <span class="caret"></span>'+
                        '</a>'+
                        '<ul class="dropdown-menu">'+
                          '<li><a class="cursorPointer" onclick="setEspacializacao(2,this,\''+strId+'\')">Municípal</a></li>' +
                          '<li><a class="cursorPointer" onclick="setEspacializacao(4,this,\''+strId+'\')">Estadual</a></li>' +
                          '<li class="divider"></li>'+
                          //'<li class="disabled customDisabled"><a onclick="setEspacializacao(3,this,\'\')"><span class="customDisabled">Regional</span></a></li>' +
                          '<li class="disabled customDisabled"><a onclick="setEspacializacao(5,this,\'\')"><span class="customDisabled">UDH</span></a></li>' +
                          '<li class="disabled customDisabled"><a onclick="setEspacializacao(6,this,\'\')"><span class="customDisabled">Região Metropolitana</span></a></li>' +
                          '<li class="disabled customDisabled"><a onclick="setEspacializacao(7,this,\'\')"><span class="customDisabled">Região de Interesse</span></a></li>' +
                          //'<li class="disabled customDisabled"><a onclick="setEspacializacao(8,this,\'\')"><span class="customDisabled">Mesorregião</span></a></li>' +
                          //'<li class="disabled customDisabled"><a onclick="setEspacializacao(9,this,\'\')"><span class="customDisabled">Microrregião</span></a></li>' +
                          //'<li class="disabled customDisabled"><a onclick="setEspacializacao(10,this,\'\')"><span class="customDisabled">País</span></a></li>' +
                        '</ul>'+
                      '</div>');
       $("#"+strId+"_spanInpSelectorEspDiv").css({"width":'130px','float':'left','text-align':'left'});
    }
    
    function setVal()
    {
        
    }
    
    function setEspacializacao(esp,e,_id)
    {
        if(_id == "") return;
        $("#"+_id).attr('value', esp);
        $("#"+_id+"_inpSelectorEsp").attr("val",esp);
        $("#"+_id+"_spanInpSelectorEsp").html(e.innerHTML);
        $("#"+_id).change();
    }