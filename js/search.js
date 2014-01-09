/* 
 *
 *author: Valter Lorran 
 *
 */

var timer;
var lastSearch = new RegExp();
var storedHTML = new RegExp();
var storedText = new RegExp();
var searchFocused = new RegExp();
var Obj;
var HolderElementSearch = new RegExp();

function setTextInput(input,espacialidade,t,listner){
    try
    {
        try{
            att = t.getAttribute('i');
        }catch(e){
            att = t.attr('i');
        }
        $("#"+input).val(t.innerHTML);
        $("#"+input).attr('i',att);
        $("#"+input).change();
        storedText[$("#"+input).attr("id")] = t.innerHTML;
        $("#"+input).click();
        listner($(t).attr('url'),att, espacialidade);
    }catch(e){
//        alert("Erro, provavelmente cidade não encontrada.");
    }
}

var Collection;
var inputHandler = {
    add: function (pai,input_name,tab,nome_municipio,show,listner) {
        strId = input_name;
        if(show){
            if(tab == '1'){
                pai.append(
                "<div class='btnInputSearch' id='area_inpt_"+input_name+"'>"+
                    '<div class="dropdown">'+
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span id="spanEsp_'+strId+'">Municípal</span><span class="caret" style="margin-top:12px;margin-left:10px;"></span></a>'+
                    '<ul class="dropdown-menu z-index-up" role="menu" aria-labelledby="dLabel">'+
                                  '<li><a class="cursorPointer" onclick="setEspacializacaoSearch(2,this,\''+strId+'\')">Municipal</a></li>' +
                                  '<li><a class="cursorPointer" onclick="setEspacializacaoSearch(4,this,\''+strId+'\')">Estadual</a></li>' +
                                  '<li class="divider"></li>'+
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(5,this,\'\')"><span class="customDisabled">UDH</span></a></li>' +
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(6,this,\'\')"><span class="customDisabled">Região Metropolitana</span></a></li>' +
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(7,this,\'\')"><span class="customDisabled">Região de Interesse</span></a></li>' +
                    '</ul>'+
                  '</div>'+
                "<a id=\"area_"+input_name+"\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
                "<input type='text' class='open2' id='"+input_name+"' i='0' in='"+tab+"' /></a></div>"+
                "<div style='display:none' id='Conten_"+input_name+"'><div style='text-align:center'>Digite o nome de algum Município ou Estado</div></div>");
            }
            else{
                pai.append(
                "<div class='btnInputSearch' id='area_inpt_"+input_name+"'>"+
                    '<div class="dropdown">'+
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span id="spanEsp_'+strId+'">Municípal</span><span class="caret" style="margin-top:12px;margin-left:10px;"></span></a>'+
                    '<ul class="dropdown-menu z-index-up" role="menu" aria-labelledby="dLabel">'+
                                  '<li><a class="cursorPointer" onclick="setEspacializacaoSearch(2,this,\''+strId+'\')">Municipal</a></li>' +
                                  '<li><a class="cursorPointer" onclick="setEspacializacaoSearch(4,this,\''+strId+'\')">Estadual</a></li>' +
                                  '<li class="divider"></li>'+
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(5,this,\'\')"><span class="customDisabled">UDH</span></a></li>' +
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(6,this,\'\')"><span class="customDisabled">Região Metropolitana</span></a></li>' +
                                  '<li class="disabled"><a onclick="setEspacializacaoSearch(7,this,\'\')"><span class="customDisabled">Região de Interesse</span></a></li>' +
                    '</ul>'+
                  '</div>'+
                "<a id=\"area_"+input_name+"\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
                "<input type='text' class='open2' id='"+input_name+"' i='0' in='"+tab+"' /></a></div>"+
                "<div style='display:none' id='Conten_"+input_name+"'><div style='text-align:center'>Digite o nome de algum Município</div></div>");
            }
        }
        
        else{
            if(tab == '1'){
                pai.append(
                    "<div class='btnInputSearch' id='area_inpt_"+input_name+"'>"+
                        "<a id=\"area_"+input_name+"\" data-placement=\"bottom\" class=\"height\">"+
                        "<input type='text' class='open2' id='"+input_name+"' i='0' in='"+tab+"' value='"+nome_municipio+"' /></a></div>"+
                    "<div style='display:none' id='Conten_"+input_name+"'><div style='text-align:center'>Digite o nome de algum Município ou Estado</div></div>"
                );
            }
            else{
                pai.append("<div class='btnInputSearch' id='area_inpt_"+input_name+"'>"+
                        "<a id=\"area_"+input_name+"\" data-placement=\"bottom\" class=\"height\">"+
                        "<input type='text' class='open2' value='Ex.: Campinas (SP)' id='"+input_name+"' i='0' in='"+tab+"' /></a></div>"+
                    "<div style='display:none' id='Conten_"+input_name+"'><div style='text-align:center'>Digite o nome de algum Município</div></div>"
                );
                $("#"+input_name).focus(function(){
                    if($(this).val() == "Ex.: Campinas (SP)"){
                        $(this).val("");
                    }
                });
                $("#"+input_name).focusout(function(){
                    if($(this).val() == ""){
                        $(this).val("Ex.: Campinas (SP)");
                    }
                });
            }
       }

                        
        //var img = '<img src="https://si0.twimg.com/a/1339639284/images/three_circles/twitter-bird-white-on-blue.png" />';
        try{
        $("#area_"+input_name).clickover({content: function() { return $('#Conten_'+input_name).html(); }, html:true });
        }catch(e){
            
        }
        input = $("#"+input_name);
        input.focusout(function(){
            searchFocused[$(this).attr("id")] = false;
            $("#area_"+$(this).attr("id")).popover('hide');
        });
        
        input.focus(function(){
            searchFocused[$(this).attr("id")] = true;
            $("#area_"+$(this).attr("id")).popover('show'); //Imprime a div com a lista de municipios
            $("#area_inpt_"+$(this).attr("id")+" .dropdown").removeClass("open");
            var teste = $(this).val();
            if(storedText[$(this).attr("id")] != $(this).val()){
                $(this).attr("i",'0');
                $(this).val(storedText[$(this).attr("id")]); 
            }
            atual = $(this);
            $("#Conten_"+$(this).attr("id")).html(storedHTML[$(this).attr("id")]);
        });
        input.keydown(function(event){
            id = $(this).attr("id");
            if(event.keyCode == 40){
                if($("#area_inpt_"+id+" .searchFocused").length){
                    last = $("#area_inpt_"+id+" .searchFocused");
                    $("#area_inpt_"+id+" .searchFocused").next("#area_inpt_"+id+" [id-m='"+id+"']").addClass("searchFocused");
                    $(last).removeClass("searchFocused");
                }else{
                    $("#area_inpt_"+id+" [id-m='"+id+"']").first().addClass("searchFocused");
                }
            }else
            if(event.keyCode == 38){
                if($("#area_inpt_"+id+" .searchFocused").length){
                    last = $("#area_inpt_"+id+" .searchFocused");
                    $("#area_inpt_"+id+" .searchFocused").prev("#area_inpt_"+id+" [id-m='"+id+"']").addClass("searchFocused");
                    $(last).removeClass("searchFocused");
                }else{
                    $("#area_inpt_"+id+" [id-m='"+id+"']").last().addClass("searchFocused");
                }
            }
            else if(event.keyCode == 13){
                if($("#area_inpt_"+id+" .searchFocused").length){
                    $(this).val($("#area_inpt_"+id+" .searchFocused").html());
                    $(this).attr('i',$("#area_inpt_"+id+" .searchFocused").attr('i'));
                    storedText[$(this).attr("id")] = $(this).val();
                    $("#area_inpt_"+id+" .searchFocused").click();
                    $(this).change();
                }else{
                    $("#area_inpt_"+id+" [id-m='"+id+"']").first().addClass("searchFocused");
                    if($("#area_inpt_"+id+" .searchFocused").length){
                        $(this).val($("#area_inpt_"+id+" .searchFocused").html());
                        $(this).attr('i',$("#area_inpt_"+id+" .searchFocused").attr('i'));
                        storedText[$(this).attr("id")] = $(this).val();
                        $("#area_inpt_"+id+" .searchFocused").click();
                        $(this).change();
                    }
                }
            }
        });
        input.keyup(function(event) {
            if(
                event.keyCode != 32 &&(
                    (event.keyCode > 16 && event.keyCode < 45) ||
                    (event.keyCode > 91 && event.keyCode < 222) || 
                    event.keyCode == 13
                )
            ){
                return;
            }
            if(event.keyCode == 8)
                $(this).attr('i',"0");
            clearInterval(timer);
            storedText[$(this).attr("id")] = $(this).val();
            $(this).attr("i",0);
            timer = window.setTimeout(function(){searchTimed($(this).attr('id'),listner)},300);
        });
    }
};

    function searchTimed(atual,listner){
        atual = $("#"+atual);
        if(atual.val() == ""){
            atual.attr('i','0');
            $("#Conten_"+atual.attr("id")).html('Digite o nome de algum Município ou Estado');
        }
        if(atual.val() == "" && atual.val() != lastSearch[atual.attr("id")])
            return;
        atual.attr("i",0);
        $("#imgLoadingInput").show();
        $("#imgLoadingInput").css('left',atual.offset().left + atual.width()+ 13);
        $("#imgLoadingInput").css('top',atual.offset().top + 8);
        $.ajax({
            type: 'post',
            data: {s:atual.val(),_in:atual.attr('in')},
            url:'com/mobiliti/util/AjaxSearchString.php',
            success: function(retorno){
                atual.attr("i",0);
                lastSearch[atual.attr("id")] = atual.val();
                Obj = jQuery.parseJSON(retorno);
                divAdd = "";
                if(Obj[0] == '003'){
                    $("#imgLoadingInput").hide();
                    atual.addClass('erroInput');
                    $("#Conten_"+atual.attr("id")).html("<div style='text-align:center'>Nenhum registro encontrado</div>");
                    if(searchFocused[atual.attr("id")])
                        $("#area_"+atual.attr("id")).clickover("show");
                    return;
                }
                if(atual.attr("in") == 2)
                {
                    for (var i in Obj){
                        atual.removeClass('erroInput');
                        divAdd += "<div i='"+Obj[i].id+"' id-m='"+atual.attr("id")+"' url='"+(replaceAllChars(' ','-',Obj[i].nome)+"_"+Obj[i].uf).toLowerCase().replace("\'", '')+"' class='divResultsPopUp' onclick=\"setTextInput('"+atual.attr("id")+"','municipio',this,"+listner+")\">"+Obj[i].nome+" ("+Obj[i].uf+")</div>";
                    }
                }else{
                    for (var i in Obj){
                        atual.removeClass('erroInput');
                        nome = Obj[i].nome;
                        id = Obj[i].id;
                        //Problemas Pará(não aparece)
                        if((nome == 'São Paulo' & id == '17') | (nome == 'Rio de Janeiro' & id == '16') | nome == 'Rondônia' | nome == 'Minas Gerais' | (nome == 'Paraná' & id == '3') |
                            nome == 'Pará' | nome == 'Amazonas' | (nome == 'Espírito Santo' & id == '6') | nome == 'Rio Grande do Sul' | nome == 'Maranhão' | nome == 'Santa Catarina' |
                            (nome == 'Goiás' & id == '10') | nome == 'Ceará' | nome == 'Bahia' | nome == 'Alagoas' | nome == 'Pernambuco' | nome == 'Rio Grande do Norte' | (nome == 'Mato Grosso' & id == '18')|
                            nome == 'Roraima' | nome == 'Paraíba' | nome == 'Mato Grosso do Sul' | nome == 'Sergipe' | nome == 'Acre' | (nome == 'Tocantins' & id == '24') | nome == 'Distrito Federal' |
                            (nome == 'Amapá' & id == '26') | nome == 'Piauí'){
                            divAdd += "<div i='"+Obj[i].id+"' id-m='"+atual.attr("id")+"' url='"+(replaceAllChars(' ','-',Obj[i].nome)+"_"+Obj[i].uf).toLowerCase()+"' class='divResultsPopUp' onclick=\"setTextInput('"+atual.attr("id")+"','estado',this,"+listner+")\">"+Obj[i].nome+"</div>";
                        }
                        else{
                            divAdd += "<div i='"+Obj[i].id+"' id-m='"+atual.attr("id")+"'url='"+(replaceAllChars(' ','-',Obj[i].nome)+"_"+Obj[i].uf).toLowerCase()+"'  class='divResultsPopUp' onclick=\"setTextInput('"+atual.attr("id")+"','municipio',this,"+listner+")\">"+Obj[i].nome+" ("+Obj[i].uf+")</div>";
                        }
                    }
                }
                storedHTML[atual.attr("id")] = divAdd;
                $('#Conten_'+atual.attr("id")).html(divAdd);

                if(searchFocused[atual.attr("id")])
                    $("#area_"+atual.attr("id")).clickover("show");
                $("#imgLoadingInput").hide();
                listner($("#area_inpt_"+atual.attr("id")+" [id-m='"+atual.attr("id")+"']").first().attr('url'));
                atual.attr("i",$("#area_inpt_"+atual.attr("id")+" [id-m='"+atual.attr("id")+"']").first().attr('i'));
            }
        });
    }
    
    function setEspacializacaoSearch(esp,e,_id)
    {
        if(_id == "") return;
        $("#spanEsp_"+_id).html(converterEspacialidadeParaStringExtenso(esp))
        $("#"+_id).attr('in', esp);
        $("#"+_id).attr('i', 0);
        $("#"+_id).val("");
        storedHTML[_id] = "<div style='text-align:center'>Digite o nome de algum Estado</div>";
        $("#Conten_"+_id).html("...");
    }
    
$(document).ready(function() {
    inputHandler.add($('#paiteste'),'lorranteste',2,false);
});
