var Json;
var tbPagina = new RegExp();
var tbOrder = 0;
var tbAno = 0;
var tbTipo = 1;
var tbOldTipo = 1;
var tbMax = 0;
var tbMaxArray = new RegExp();
var tbObjetoConsulta;
var JSONOrder = new RegExp();
var nomesSalvo;
var idsSalvo;
var holderCount = 0;
var count = 1;
var counterColumns = 0;
var storedVirtual = 0;
var jsonTESTE = new RegExp();
var AnoOrdem = 0;
var IdOrdem = 0;
var OrOrdem = true;
var index = 0;
var offsetCounter = 0;
var copyObject = new Array();
var cidadesRemovidas = new Array();
var cidadesRemovidas2 = new Array();
var storedText = "";
var flag_value_init = 0;
var flag_value_fina = 0;
var contador_tabela = 0;
var isLoadingBottom = false;
var isLoadingTop = false;
var conectionID_FK = new Object();
var holdLoading = false;
var ADD_ON_SCROLL = 20;
var REMOVE_ON_SCROLL = 15;
var TIME_LOADING_ROW = 700;
var listaEstados = new Object();
var estado_has_municipio = new Object();
var jsonEstados;
var countries = new Array();

function tabela_build(){
    holdLoading = true;
    loadingHolder.show("Carregando dados...");
    indicadores = geral.getIndicadores();
    for(var i in JSONOrder)
        delete JSONOrder[i];
    if(indicadores.length == 0){
        //        indicadores = JSON.parse('[{"id":196,"a":3}]');
        //	var indicador = new IndicadorPorAno();
        //	indicador.id = 196;
        //	indicador.nc = "IDHM";
        //	indicador.sigla = "IDHM";
        //	indicador.c = true;
        //	indicador.desc = "Média geométrica dos índices das dimensões Renda, Educação e Longevidade, com pesos iguais.";
        //	indicador.a = 3;
        //        geral.addIndicador(indicador);
        //        geral.removeIndicador(0);

        lugares = geral.getLugares();
        t = 0;
        for(var i in lugares){
            if(lugares[i].l.length != 0){
                t++;
            }
        }
        if(t == 0){
            fillEnptyTabela();
            loadingHolder.dispose();
            return;
        }
        tbObjetoConsulta = new Object();
        tbObjetoConsulta["nomevariaveis"] = [];
        for(var i in lugares){
            for(var k in lugares[i].l){
                idTemp = "";
                (lugares[i].l[k]);
                switch(lugares[i].l[k].e){
                    case 4:
                        idTemp = lugares[i].l[k].id + "e";
                        break;
                    case 2:
                        idTemp = lugares[i].l[k].id;
                        break;
                    case 7:
                        idTemp = lugares[i].l[k].id+'i';
                        break;
                }
                _nome = lugares[i].l[k].n;
                if(_nome.indexOf("(") == -1){
                    tempObj = {
                        esp:lugares[i].l[k].e,
                        id:idTemp,
                        nome:_nome,
                        empty:true,
                        sortby:lugares[i].l[k].n,
                        vs:[]
                    };
                }else{
                    pos = _nome.split("(");
                    _uf = pos[1].replace(")","");
                    tempObj = {
                        esp:lugares[i].l[k].e,
                        id:idTemp,
                        nome:pos[0],
                        uf:_uf,
                        sortby:_nome,
                        vs:[]
                    };
                }
                //                tbObjetoConsulta.push(tempObj)
                tbObjetoConsulta[idTemp] = tempObj;
            }
        }
        holdLoading = false;
        setOrder(tbObjetoConsulta,0,0,0);
        $(".customOrdenarTabela").css("background","#F00");
        //        fillTabela(tbObjetoConsulta);
        return;
        
    }
    try{
        lugaresH = geral.getLugaresString();
        lugares = geral.getLugares();
        if(geral.getLugares().length <= 0){
            fillEnptyTabela();
            $(".customOrdenarTabela").css("display","none");
            loadingHolder.dispose();
            return;
        }
        getEstados();
        cont_lugs = 0;
        lugs = geral.getLugares();
        for(var i in lugs){
            if(lugs[i].e == 7){
                for(var k in lugs[i].l){
                    ge = geral.getAreaTematica(lugs[i].l[k].id).getSize();
                    if(ge != null){
                        cont_lugs += parseInt(ge);
                    }
                }
            }
        }
        $.ajax({
            type: 'post',
            url:'com/mobiliti/tabela/tabela.controller.php',
            data:{
                'json_lugares':lugaresH,
                'json_indicadores' : geral.getIndicadoresString(),
                'count_lugs' : cont_lugs
            },
            success: function(retorno){
                holdLoading = false;
                //                try{
                countries = new Array();
                tbObjetoConsulta = jQuery.parseJSON(retorno);
                $("#imgTab6").show();
                if(typeof(tbObjetoConsulta.erro) != 'undefined'){
                    $("#imgTab6").hide();
                    if(tbObjetoConsulta.erro == 1){
                        $("#tableConsulta_1 tbody").html('<tr><td colspan="100%" style="width:870px;text-align:center;padding-top:20px"><br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> '+tbObjetoConsulta.msg+'</div></td></tr>');
                    }
                    if(tbObjetoConsulta.erro == 99){
                        $("#tableConsulta_1 tbody").html('<tr><td colspan="100%" style="width:870px;text-align:center;padding-top:20px"><br /><br /><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button> '+tbObjetoConsulta.msg+'</div></td></tr>');
                    }
                    loadingHolder.dispose();
                    return;
                }
                for(var k in tbObjetoConsulta){
                    if(typeof(tbObjetoConsulta[k].country)!= "undefined"){
                        countries.push(k);
                        break;
                    }
                }
                if($("#form-download").length){
                    $("#form-download").remove();
                }
                if(typeof(tbObjetoConsulta.download) != "undefined"){
                    loadingHolder.dispose();
                    form = '<form method="post" target="_blank" action="consulta/download/" id="form-download"><input type="hidden" value="'+encodeURIComponent(retorno)+'" name="crossdata" /></form>'
                    $("body").append(form);
                    $("#staticThead_1").remove();
                    $("#imgTab6").attr("disabled","true");
                    $("#localTabelaConsulta").html("<tr><td colspan='100%' style='width:870px;text-align:center;padding-top:20px'><div style='text-align:center'>Download da tabela:</div><div style='text-align:center'><button id='btnDownloadLista' class='gray_button big_bt' style='float:none' data-original-title='Download da lista em formato csv (compatível com o Microsoft Excel e outras planilhas eletrônicas).' title data-placement='bottom' icon='download_2'>"+
                        "<img src='img/icons/download_2.png'/>"+
                        "</button></div></td></tr>");
                    $("#btnDownloadLista").click(function(){
                        $("#form-download").submit();
                    });
                    return;
                }
                estado_has_municipio = new Object();
                for(var i in lugares){
                    if(lugares[i].e == 2){
                        if(lugares[i].l.length > 0)
                        {
                            for(var k in lugares[i].l){
                                if(typeof(lugares[i].l[k].n) != "undefined"){
                                    if(typeof(tbObjetoConsulta[lugares[i].l[k].id.toString()+"i"]) == "undefined"){
                                        nome = (lugares[i].l[k].n).split('(');
                                        uf = nome[1].replace(")", "");
                                        tbObjetoConsulta[lugares[i].l[k].id].nome = nome[0];
                                        tbObjetoConsulta[lugares[i].l[k].id].uf = uf;

                                        tbObjetoConsulta[lugares[i].l[k].id].vs = ordenarVariaveis(tbObjetoConsulta[lugares[i].l[k].id].vs);
                                        if(typeof(estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id].uf]) == "undefined"){
                                            estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id].uf] = new Object();
                                            estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id].uf].ms = new Array();
                                            estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id].uf].uf = tbObjetoConsulta[lugares[i].l[k].id].uf;
                                        }
                                        estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id].uf].ms.push([nome[0],lugares[i].l[k].id]);
                                    }else{
                                        delete tbObjetoConsulta[lugares[i].l[k].id];
                                    }
                                }
                            }
                        }
                    }else if(lugares[i].e == 4){
                        listaEstados = new Object();
                        for(var k in lugares[i].l){
                            if(typeof(tbObjetoConsulta[lugares[i].l[k].id+"e"]) != "undefined" && tbObjetoConsulta[lugares[i].l[k].id+"e"] != null){
                                tbObjetoConsulta[lugares[i].l[k].id+"e"].nome = lugares[i].l[k].n;
                                tbObjetoConsulta[lugares[i].l[k].id+"e"].vs = ordenarVariaveis(tbObjetoConsulta[lugares[i].l[k].id+"e"].vs);
                                listaEstados[tbObjetoConsulta[lugares[i].l[k].id+"e"].u] = new Object();
                                listaEstados[tbObjetoConsulta[lugares[i].l[k].id+"e"].u].nome = lugares[i].l[k].n;
                                listaEstados[tbObjetoConsulta[lugares[i].l[k].id+"e"].u].id = lugares[i].l[k].id;
                                if(typeof(estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id+"e"].u]) == "undefined"){
                                    estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id+"e"].u] = new Object();
                                    estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id+"e"].u].ms = new Array();
                                    estado_has_municipio[tbObjetoConsulta[lugares[i].l[k].id+"e"].u].uf = tbObjetoConsulta[lugares[i].l[k].id+"e"].u;
                                }
                            }
                        }
                    }else if(lugares[i].e == 7){
                            
                        if(lugares[i].l.length > 0)
                        {
                            for(var k in tbObjetoConsulta){
                                if(k == "nomevariaveis" || typeof(tbObjetoConsulta[k].is_ri) == "undefined")
                                    continue;
                                tbObjetoConsulta[k].vs = ordenarVariaveis(tbObjetoConsulta[k].vs);
                                if(typeof(estado_has_municipio[tbObjetoConsulta[k].is_ri]) == "undefined"){
                                    estado_has_municipio[tbObjetoConsulta[k].is_ri] = new Object();
                                    estado_has_municipio[tbObjetoConsulta[k].is_ri].ms = new Array();
                                    estado_has_municipio[tbObjetoConsulta[k].is_ri].uf = tbObjetoConsulta[k].is_ri;
                                }
                                
                                //                                jj = k.replace("i","");
                                //                                
                                //                                if(typeof(tbObjetoConsulta[jj]) != "undefined"){
                                //                                    delete tbObjetoConsulta[jj];
                                //                                }
                                
                                estado_has_municipio[tbObjetoConsulta[k].is_ri].ms.push([tbObjetoConsulta[k].nome,k]);
                                estado_has_municipio[tbObjetoConsulta[k].is_ri].is_ri = tbObjetoConsulta[k].is_ri;
                            }
                        }
                    }
                }
                if(typeof(tbObjetoConsulta.correcao) != 'undefined'){
                    splited = UrlController.getUrl('tabela').split("/");
                    if(splited[splited.length - 1] == "indicador"){
                        splited.push("idhm-2010");
                        url = splited.join("/");
                        UrlController.setUrl("tabela", url);
                        loadingHolder.dispose();
                    }
                }
                toOrder = new Array();
                for(var i in estado_has_municipio){
                    if(typeof(jsonEstados[i])!= "undefined"){
                        estado_has_municipio[i].nome = retira_acentos(jsonEstados[i]).toLowerCase();
                    }else{
                        if(typeof(estado_has_municipio[i].is_ri) != "undefined"){
                            estado_has_municipio[i].nome = estado_has_municipio[i].is_ri;
                        }else
                            estado_has_municipio[i].nome = "zz";
                    }
                    toOrder.push(estado_has_municipio[i]);
                }
                toOrder.sort(dynamicSort("nome"));
                estado_has_municipio = new Object();
                for(var i in toOrder){
                    estado_has_municipio[toOrder[i].uf] = new Object();
                    estado_has_municipio[toOrder[i].uf] = toOrder[i];
                }
                   
                var agrupados = new Array();
                for(var i in countries){
                    tbObjetoConsulta[countries[i]].vs = ordenarVariaveis(tbObjetoConsulta[countries[i]].vs);
                    agrupados.push(tbObjetoConsulta[countries[i]]);
                }
                for(var i in estado_has_municipio){
                    if(typeof(listaEstados[i])!= "undefined"){
                        estado_has_municipio[i].nome = listaEstados[i].nome;
                        agrupados.push(tbObjetoConsulta[listaEstados[i].id+"e"]);
                        //                            agrupados.push(listaEstados[i].id+"e");
                        for(var k in estado_has_municipio[i].ms){
                            //                                agrupados.push(estado_has_municipio[i].ms[k][1]);
                            agrupados.push(tbObjetoConsulta[estado_has_municipio[i].ms[k][1]]);
                        }
                    }else{
                        if(estado_has_municipio[i].ms.length > 0){
                            //                                if(typeof(estado_has_municipio[i].ms[k]) != "undefined")
                            for(var k in estado_has_municipio[i].ms){
                                agrupados.push(tbObjetoConsulta[estado_has_municipio[i].ms[k][1]]);
                            }
                            estado_has_municipio[i].nome = jsonEstados[i];
                        }
                    }
                }
                //                
                //                for(var k in tbObjetoConsulta){
                //                    if(k == "nomevariaveis" || typeof(tbObjetoConsulta[k].is_ri) == "undefined")
                //                        continue;
                //                    if(k.indexOf("i") < 0)
                //                        continue;
                //                    c = k.replace("i","");
                //                    if(typeof(agrupados[c]) != "undefined"){
                //                        delete agrupados[c];
                //                    }
                //                }
                //                    if(agrupados.length > 0){
                //                return;
                fillTabela(agrupados);
            //                    }else{
            //                        if(IdOrdem == 0 || AnoOrdem == 0)
            //                            setOrder(this,0,0,0);
            //                        else{
            //                            setOrder(this, IdOrdem, AnoOrdem, IdOrdem+"_"+AnoOrdem,OrOrdem);
            //                            IdOrdem = 0;
            //                            AnoOrdem = 0;
            //                        }
            //                    }
            //                }catch(e){
            //                    holdLoading = false;
            //                    addLogErro("build.tabela.js", 58, 98, e.message.replace(",","(vir)"));
            //                    loadingHolder.dispose();
            //                    $("#containerTabela").html('<br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> Click <a class="linkAlert" onclick="javascript:location.reload();">aqui</a> para recarregar a página. Cod: #002</div>');
            //                }
            }
        });
    }catch(e){
        loadingHolder.dispose();
        holdLoading = false;
        $("#containerTabela").html('<br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> Click <a class="linkAlert" onclick="javascript:location.reload();">aqui</a> para recarregar a página. Cod: #003</div>');
        addLogErro("build.tabela.js", 52, 108, e.message.replace(",","(vir)"));
    }
}

function dynamicSort(property) {
    var sortOrder = 1;
    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1, property.length - 1);
    }
    return function (a,b) {
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}

function setOrdemUrl(id,ano,ord){
    IdOrdem  = id;
    AnoOrdem = ano;
    OrOrdem = ord;
}

function ordenarVariaveis(obj){
    //        keys = Object.keys(obj)
    //        keys.sort();
    //        newOb = new Object();
    //        
    //        for(var i in keys){
    //            newOb[keys[i]] = obj[keys[i]];
    //        }
    
    
    keys = Object.keys(obj)
    newOb = new Object();
    arr = new Object();
    
    retorno = obj;
    newOb = new Object();
    indicadores = geral.getIndicadores();
    for(var i in indicadores){
        if(typeof(arr["i"+indicadores[i].id]) == "undefined")
            arr["i"+indicadores[i].id] = new Array();
        arr["i"+indicadores[i].id].push(indicadores[i].a);
        arr["i"+indicadores[i].id].sort();
    //        newOb[indicadores[i].id+"_"+indicadores[i].a] = retorno[indicadores[i].id+"_"+indicadores[i].a];
    }
    for(var i in arr){
        for(var k in arr[i]){
            newOb[i.replace("i","")+"_"+arr[i][k]] = retorno[i.replace("i","")+"_"+arr[i][k]];
        }
    }
    //    asdasdsad;
    //    retorno = obj;
    //    newOb = new Object();
    //    indicadores = geral.getIndicadores();
    //    for(var i in indicadores){
    //        newOb[indicadores[i].id+"_"+indicadores[i].a] = retorno[indicadores[i].id+"_"+indicadores[i].a];
    //    }
    return newOb;
}

function getCSV(){
    json = copyObject;
    result = "sep = ;\n";
    t= true;
    for(var i in json){
        if(i == "nomevariaveis")
        {
            continue;
        }
        _j = json[i];
        c = 0;
        if ($("#tableConsulta_1").length == 0){
            tbPagina[i] = 1;
        }
        sizeCount = Object.keys(_j).length;
        
        p = sizeCount - 2;
        hold = p;
        id = "trConsulta_1_"+p;
        put = new Array();
        if(t){
            
            put.push("Lugar");
            for(var k in json[i].vs){
                put.push(tbObjetoConsulta['nomevariaveis'][json[i].vs[k].iv].nomecurto+" ("+traduzirAno(json[i].vs[k].ka)+")");
            }
            result += put.join('; ')+"\n";
            put = new Array();
            t = false;
        }
        tcity = replaceAllChars(" ", "-", json[i].nome);
        if(typeof(json[i].uf) != "undefined")
            put.push(json[i].nome+" ("+json[i].uf+")");
        else
            put.push(json[i].nome);
        
        for(var k in json[i].vs){
            if(json[i].vs[k].v == -1){
                put.push(' ');
            }else
                put.push(json[i].vs[k].v.replace('.', ','));
        }
        result += put.join('; ')+"\n";
        count++;
    }
    return result;
}

function addNewColumnValuesVirtual(sigla,ano,nome,id){
    storedVirtual++;
    json = '[{"id":'+id+',"a":3}]';
    loadingHolder.show("Carregando dados...");
    $.ajax({
        type: 'post',
        url:'com/mobiliti/tabela/tabela.controller.php',
        data:{
            'json_lugares':geral.getLugares(),
            'json_indicadores':JSON.parse(json),
            'dataBring':'var_only'
        },
        success: function(retorno){
            try{
                y = ano;
                jAnswer = jQuery.parseJSON(retorno);
                for(var i in jAnswer){
                    if(typeof(jAnswer[i].im) != "undefined"){
                        tbObjetoConsulta[jAnswer[i].im].vs["virtual_"+storedVirtual+"_"+jAnswer[i].iv+"_"+y] = {
                            ka:y.toString(),
                            iv:jAnswer[i].iv,
                            v:jAnswer[i].v
                        };
                    }
                }
                tbObjetoConsulta.nomevariaveis[jAnswer[0].iv] = {
                    id:jAnswer[0].iv, 
                    nomecurto:nome, 
                    sigla:sigla, 
                    definicao:jAnswer.nomevariaveis[jAnswer[0].iv].definicao
                };
                id = "#columnTitle_"+jAnswer[0].iv+"_"+y+" .titleDiv";
                fillTabela(tbObjetoConsulta);
            }catch(e){
                loadingHolder.dispose();
                $("#containerTabela").html('<br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> Click <a class="linkAlert" onclick="javascript:location.reload();">aqui</a> para recarregar a página. Cod: #003</div>');
            }
        }
    });
}

function addNewColumnValues(sigla,ano,nome,cod,id){
    json = '[{"id":'+id+',"a":3}]';
    lug = geral.getLugares();
    t = 0;
    for(var i in lug){
        if(lug[i].l.length != 0){
            t++;
        }
    }
    if(t == 0){
        fillEnptyTabela();
        loadingHolder.dispose();
        return;
    }
    //    if(geral.getIndicadores().length == 0){
    //        tabela_build();
    //    }
    $.ajax({
        type: 'post',
        url:'com/mobiliti/tabela/tabela.controller.php',
        data:{
            'json_lugares':geral.getLugares(),
            'json_indicadores': JSON.parse(json),
            'dataBring':'var_only'
        },
        success: function(retorno){
            try{
                y = ano;
                jAnswer = jQuery.parseJSON(retorno);
                sizeCount = Object.keys(jAnswer).length;
                if(cod == 0){
                    for(var i in jAnswer){
                        if(typeof(jAnswer[i].im) != "undefined"){
                            tbObjetoConsulta[jAnswer[i].im].vs[jAnswer[i].iv+"_"+y] = {
                                ka:y.toString(),
                                iv:jAnswer[i].iv,
                                v:jAnswer[i].v
                            };
                        }
                    }
                    tbObjetoConsulta.nomevariaveis[jAnswer[0].iv] = {
                        id:jAnswer[0].iv, 
                        nomecurto:nome, 
                        sigla:sigla, 
                        definicao:jAnswer.nomevariaveis[jAnswer[0].iv].definicao
                    };
                    id = "#columnTitle_"+jAnswer[0].iv+"_"+y+" .titleDiv";
                    fillTabela(tbObjetoConsulta);
                }else{
                    $("#staticThead_1 [tdId='"+cod+"']").html(
                        "<div class='columnTitle' id='columnTitle_"+jAnswer[0].iv+"_"+y+"'>"+
                        "<div class='tableNodeDiv' s='idhm'>"+
                        "<div class='titleDiv'>"+nome+"</div>"+
                        "<div class='tableAnoDiv'>"+ano+"</div>"+
                        "</div>"+
                        "</div>");
                    $("#staticThead_1 [tdId='"+cod+"']").attr("tdId",jAnswer[0].iv+'_'+y);

                    $("th").hover(
                        function(){
                            id = $(this).attr("tdId");
                            $("#remove_"+id).removeClass("icon-white");
                        },function(){
                            id = $(this).attr("tdId");
                            $("#remove_"+id).addClass("icon-white");
                        });
                    $("#staticThead_2 [tdId='"+cod+"']").attr("tdId",jAnswer[0].iv+'_'+y);
                    $("#staticThead_2 [tdId='"+cod+"']").html("<div id='remove_"+jAnswer[0].iv+"_"+y+"' class=\"customRemoveColumn\" onclick=\"removerColuna("+jAnswer[0].iv+","+y+",'"+sigla+"','"+cod+"',"+index+");\"></div>");
                    for(var i in jAnswer){
                        $("#tableConsulta_1").children("tbody").children("[tdId='"+cod+"']").html(jAnswer[i].v);
                        $("#tableConsulta_1").children("tbody").children("[tdId='"+cod+"']").attr("tdId",jAnswer[i].iv+'_'+y);
                        if(typeof(jAnswer[i].im) != "undefined"){
                            tbObjetoConsulta[jAnswer[i].im].vs[jAnswer[i].iv+"_"+y] = {
                                ka:y.toString(),
                                iv:jAnswer[i].iv,
                                valor:jAnswer[i].v
                            };
                        }
                    }
                    tbObjetoConsulta.nomevariaveis[jAnswer[0].iv] = {
                        id:jAnswer[0].iv, 
                        nomecurto:nome, 
                        sigla:sigla, 
                        definicao:jAnswer.nomevariaveis[jAnswer[0].iv].definicao
                    };
                    id = "#columnTitle_"+jAnswer[0].iv+"_"+y+" .titleDiv";
                }
                if(nome.length < 35)
                    $(id).css("font-size",'16px');
                else if(nome.length < 44)
                    $(id).css("font-size",'12px');
                else if(nome.length < 55)
                    $(id).css("font-size",'10px');
                else if(nome.length < 75)
                    $(id).css("font-size",'10px');
                else if(nomecurto.length < 200)
                    $(id).css("font-size",'9.5px');
                index++;
            }catch(e){
                addLogErro("build.tabela.js", 221, 294, e.message.replace(",","(vir)"));
                loadingHolder.dispose();
                $("#containerTabela").html('<br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> Click <a class="linkAlert" onclick="javascript:location.reload();">aqui</a> para recarregar a página. Cod: #004</div>');
            }
        }
    });
}

function getCountIndicadores(){
    return geral.getIndicadores().length;
}

function changeYear(ano,old,id,element,k,idx){
    storedVirtual++;
    sigla = tbObjetoConsulta['nomevariaveis'][id].sigla.toLowerCase();
    json = '[{"id":'+id+',"a":'+ano+'}]';
    
    if(geral.getLugares().length > 0){
        loadingHolder.show("Carregando dados...");
        $.ajax({
            type: 'post',
            url:'com/mobiliti/tabela/tabela.controller.php',
            data:{
                'json_lugares':geral.getLugares(), 
                'json_indicadores':JSON.parse(json),
                'dataBring':'var_only'
            },
            success: function(retorno){
                try{
                    y = ano;
                    jAnswer = jQuery.parseJSON(retorno);
                    for(var i in jAnswer){
                        if(typeof(jAnswer[i].im) != "undefined"){
                            tbObjetoConsulta[jAnswer[i].im].vs[k] = {
                                ka:y.toString(),
                                iv:jAnswer[i].iv,
                                v:jAnswer[i].v
                            };
                        }
                    }
                    geral.updateIndicador(idx,ano);
                    tbObjetoConsulta.nomevariaveis[jAnswer[0].iv] = {
                        id:jAnswer[0].iv, 
                        nomecurto:tbObjetoConsulta['nomevariaveis'][id].nomecurto, 
                        sigla:sigla
                    };
                    fillTabela(tbObjetoConsulta);
                }catch(e){
                    loadingHolder.dispose();
                    $("#containerTabela").html('<br /><br /><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Erro na requisição: </strong> Click <a class="linkAlert" onclick="javascript:location.reload();">aqui</a> para recarregar a página. Cod: #005</div>');
                }
            }
        });
    }else{
        geral.updateIndicador(idx, ano);
        fillEnptyTabela();
    }
    
    return;
    y = convertAno(ano);
    sigla = tbObjetoConsulta['nomevariaveis'][id].sigla.toLowerCase();
    tabela_build('ioa');
    return;
    $("#columnTitle_"+id+"_"+old+" .tableAnoDiv").html(ano);
    $("#columnTitle_"+id+"_"+old+" .marked").removeClass("marked");
    //$("[tdId='"+id+"_"+old+"']").attr("tdId", id+"_"+y);
    addNewColumnValues(sigla,ano,tbObjetoConsulta['nomevariaveis'][id].nomecurto,id+"_"+old);
    $(element).addClass("marked");
}

function paginaPrevious(Esp){
    if(tbPagina[Esp] > 1){
        tbPagina[Esp] = tbPagina[Esp] - 1;
        tabela_build(Json,tbPagina[Esp],tbOrder,tbAno,Esp);
    }
}

var sort_by = function(field, reverse, primer){

    var key = function (x) {
        return primer ? primer(x[field]) : x[field]
    };

    return function (a,b) {
        var A = key(a), B = key(b);
        return ( (A < B) ? -1 : ((A > B) ? 1 : 0) ) * [-1,1][+!!reverse];                
    }
}

function setOrder(ob,order,ano,k,ord){
    t = 0;
    for(var i in lug){
        if(lug[i].l.length != 0){
            t++;
        }
    }
    if(t == 0){
        return;
    }
    loadingHolder.show("Preenchendo tabela...");
    $("#columnTitle_"+order+"_"+ano+ " .open").removeClass("open");
    loadingHolder.show("Preenchendo tabela...");

    setTimeout(function(){
        runOrderSet(ob,order,ano,k,ord);  
    },20);
}

function runOrderSet(ob,order,ano,k,ord){
    
    if(typeof(JSONOrder[order+"_"+ano]) == 'undefined')
        JSONOrder[order+"_"+ano] = true;
    JSONOrder[order+"_"+ano] = !JSONOrder[order+"_"+ano];
    
    if(typeof(ord) != "undefined"){
        JSONOrder[order+"_"+ano] = ord;
    }
    
    a = $("#titleDiv_1_"+order+"_"+ano).next(".tableAnoDiv").html();
    if(order == 0)
    {
        var array = new Array();
        for(var i in tbObjetoConsulta){
            if(i == "nomevariaveis")
            {
                continue;
            }
            tbObjetoConsulta[i]['sortby'] = tbObjetoConsulta[i].nome;
            array.push(tbObjetoConsulta[i]);
        }
        array.sort(sort_by('sortby', !JSONOrder[order+"_"+ano], function(a){
            return retira_acentos(a.toUpperCase())
        }));
        fillTabela(array);
        //        $(".ordenadoArrow").removeClass("ordenadoArrow");
        //        $(ob).addClass("ordenadoArrow");
        $("#setOrder_"+k).addClass("ordenadoArrow");
        $("#setOrder_"+k).parent().addClass("ordenadoArrowFather");
        if(JSONOrder[order+"_"+ano])
            $("#setOrder_"+k).css("background","url(img/icons/up.png) no-repeat");
        else
            $("#setOrder_"+k).css("background","url(img/icons/down.png) no-repeat");
        return;
    }
    //    alert($("#staticThead_2").children("[tdid='"+k+"']").children(".customOrdenarTabela").html());
        
    var array = new Array();
    for(var i in tbObjetoConsulta){
        if(i == "nomevariaveis")
        {
            continue;
        }
        tbObjetoConsulta[i]['sortby'] = tbObjetoConsulta[i].vs[k].v;
        array.push(tbObjetoConsulta[i]);
    }
    
    array.sort(sort_by('sortby', JSONOrder[order+"_"+ano], parseFloat));
    
    fillTabela(array);
    $("#setOrder_"+k).addClass("ordenadoArrow");
    $("#setOrder_"+k).parent().addClass("ordenadoArrowFather");
    if(JSONOrder[order+"_"+ano])
        $("#setOrder_"+k).css("background","url(img/icons/up.png) no-repeat");
    //        tex += " (ascendente)";
    else
        $("#setOrder_"+k).css("background","url(img/icons/down.png) no-repeat");
//        tex += " (descendente)";
}

function paginaFirst(Esp){
    if(tbPagina[Esp] == 1){
        return;
    }
    tbPagina[Esp] = 1;
    tabela_build(Json,tbPagina[Esp],tbOrder,tbAno,Esp);
}

function paginaLast(Esp){
    if(tbPagina[Esp] == tbMaxArray[Esp]){
        return;
    }
    tbPagina[Esp] = tbMaxArray[Esp];
    tabela_build(Json,tbPagina[Esp],tbOrder,tbAno,Esp);
}

function traduzirAno(ano){
    switch(ano){
        case '1':
            return 1991;
        case '2':
            return 2000;
        case '3':
            return 2010;
        case 1:
            return 1991;
        case 2:
            return 2000;
        case 3:
            return 2010;
        default:
            return 0;
    }
}

function removerColuna(coluna,ano,sigla,k,j){
    for(var i in tbObjetoConsulta){
        if(i == "nomevariaveis")
        {
            continue;
        }
        delete tbObjetoConsulta[i].vs[k];
    }
    geral.removeIndicador(j);
    if(geral.getLugares().length == 0){
        fillEnptyTabela();
    }
    else{
        fillTabela(tbObjetoConsulta);
    }
    return;
    $("[tdId='"+k+"']").each(function(){
        $(this).remove();
    });
    if(getCountIndicadores() < 20 && !("#tabelaSelectorIndcadorSingle").length)
        newTdBlank();
}

function fillEnptyTabela(){
    index = 0;
    $("#localTabelaConsulta").html("");
    $("#localTabelaConsulta").append(
        "<div class='fixedTable'><table cellspacing=\"0px\" class='tableConsulta tabelaStyle\' cellpadding=\"0px\" id='tableConsulta_1'>"+
        "<thead  class='staticThead' id=\"staticThead_2\"></thead>"+
        "<thead class='staticThead' id=\"staticThead_1\">"+
        "</thead>"+
        "<tr>"+
        "    <td></td>"+
        "</tr>"+
        "</table></div><div class='clear'></div>"
        );
            
    t_geral = false;
    g =  geral.getLugares();
    
    for(var i in g){
        if(g[i].l.length > 0){
            t_geral = true;
            break;
        }
    }
    
    if(!t_geral)
        $("#staticThead_1").append("<th class='th_1 th_2' >"+
            "<div class='btnInputSearch' id='area_inpt_btnPesquisaTabela'>"+
            "<a id=\"area_btnPesquisaTabela\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
            "</div><div style='display:none' id='Conten_btnPesquisaTabela'>"+
            "<div style='text-align:center'></div>"+
            "</div></th>");
    else        
        $("#staticThead_1").append("<th class='th_1 th_2' >"+
            "<div class='btnInputSearch width250px' id='area_inpt_btnPesquisaTabela'>"+
            "<a id=\"area_btnPesquisaTabela\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
            "<input type='text' class='open2 customInputTabela' id='btnPesquisaTabela' i='0' in='2' value='"+storedText+"'/></a>"+
            "</div><div style='display:none' id='Conten_btnPesquisaTabela'>"+
            "<div style='text-align:center'></div>"+
            "</div><i class=\"icon-search customIconTabela\"></i></th>");
    $("#staticThead_2").append("<th class='th_1 th_2' ><div class='width250px'></div></th>");
    k = 0;
    g = geral.getIndicadores();
    if(g.length  > 0)
    {
        for(var i in g){
            try
            {
                nomecurto = g[i].nc;
                definicao = g[i].nc;
            }
            catch(e){
                addLogErro("build.tablea.js", "462", "473", e);
                continue;
            }
            if(typeof(definicao) == "undefined"){
                definicao = nomecurto;
            }
            ano = traduzirAno(g[i].a);
            anos = "";
            if(ano == 1991){
                anos = "<k><b>1991</b></k>";
            }else if(ano == 2000){
                anos = "<k><b>2000</b></k>";
            }else if(ano == 2010){
                anos = "<k><b>2010</b></k>";
            }
            $("#staticThead_1").append("<th tdId='"+k+"'>"+
                "<div class='columnTitle' id='columnTitle_"+k+"'>"+
                "<div class='columnTitle_sub'>"+
                "<div class='titleDiv' data-original-title='"+definicao+"' title data-placement='left' id='titleDiv_1_"+k+"'>"+nomecurto+"</div>"+
                "<div class='tableAnoDiv'>"+anos+"</div>"+
                "</div>"+
                "</div>"+
                "</th>");
            $("#titleDiv_1_"+k+"").tooltip({
                delay: 500
            });
            $("#staticThead_2").append("<th tdId='"+k+"'>"+
                "<div id='remove_"+k+"' class=\"customRemoveColumn\" onclick=\"removerColuna(0,0,0,'0',"+index+");\"></div>"+
                "<div id='setOrder_"+k+"' class=\"customOrdenarTabela\" onclick=\"setOrder(this,"+g[i].id+","+g[i].a+",'"+k+"')\"></div>"+
                "</th>");

            if(nomecurto.length < 35)
                $("#titleDiv_1_"+k).css("font-size",'16px');
            else if(nomecurto.length < 44)
                $("#titleDiv_1_"+k).css("font-size",'12px');
            else if(nomecurto.length < 55)
                $("#titleDiv_1_"+k).css("font-size",'10px');
            else if(nomecurto.length < 75)
                $("#titleDiv_1_"+k).css("font-size",'10px');
            else if(nomecurto.length < 200)
                $("#titleDiv_1_"+k).css("font-size",'9.5px');

            index++;
            k++;
        }
    }
    $(".tableConsulta tbody").html("");
    newTdBlank();
}

function fillTabela(json){
    copyObject = new Array();
    flag_value_fina = 0;
    flag_value_init = 0;
    //    copyObject = json;
    counter = 0;
    index = 0;
    //
    $("#localTabelaConsulta").html("");
    $("#localTabelaConsulta").append(
        "<div class='fixedTable'><table cellspacing=\"0px\" cellpadding=\"0px\" class='table-master-consulta'><tr><td class='td-master-tabela table-master-consulta-1'><table cellspacing=\"0px\" class='tableConsulta tabelaStyle\' style='min-height:0px' cellpadding=\"0px\" >"+
        //            "<thead  class='staticThead' id=\"staticThead_2\"></thead>"+
        "<thead class='staticThead' id=\"staticThead_1\">"+
        "</thead></table></td></tr><tr><td class='td-master-tabela'><div id='second-td-scroll'><table  class='tableConsulta tabelaStyle'  id='tableConsulta_1'>"+
        "<tr>"+
        "    <td>Loading</td>"+
        "</tr>"+
        "</table></div></td></tr></table></div><div class='clear'></div>"
        );
            
    lug = geral.getLugares();
    t = 0;
    for(var i in lug){
        if(lug[i].l.length != 0){
            t++;
        }
    }
    if(t > 0)
        $("#staticThead_1").append("<th class='th_hover th_1 th_2' >"+
            "<div id='setOrder_0' class=\"customOrdenarTabela\" onclick=\"setOrder(this,0,0,0)\" data-original-title='Ordenar' title data-placement='bottom'></div><div id='classUperStatic' style='clear: both; height:20px'></div>"+
            "<div class='btnInputSearch' id='area_inpt_btnPesquisaTabela'>"+
            "<a id=\"area_btnPesquisaTabela\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
            "<input type='text' class='open2 customInputTabela'  value='"+storedText+"' id='btnPesquisaTabela' i='0' in='2' /></a>"+
            "</div><div style='display:none' id='Conten_btnPesquisaTabela'>"+
            "<div style='text-align:center'></div>"+
            "</div><i class=\"icon-search customIconTabela\"></i></th>");
    else{
        $("#staticThead_1").append("<th class='th_hover th_1 th_2' >"+
            "<div id='setOrder_0' class=\"customOrdenarTabela\" onclick=\"setOrder(this,0,0,0)\" data-original-title='Ordenar' title data-placement='bottom'></div><div id='classUperStatic' style='clear: both; height:20px'></div>"+
            "<div class='btnInputSearch' id='area_inpt_btnPesquisaTabela'>"+
            "<a id=\"area_btnPesquisaTabela\" data-placement=\"bottom\" class=\"height\" rel=\"popover\">"+
            "</div><div style='display:none' id='Conten_btnPesquisaTabela'>"+
            "<div style='text-align:center'></div>"+
            "</div></th>");
        if(geral.getIndicadores().length == 0){
            fillEnptyTabela();
            return;
        //            tbObjetoConsulta = new RegExp();
        }
    }
    //    $("#staticThead_2").append("<th class='th_1 th_2' ></th>");
    $("#setOrder_0").tooltip({
        delay: 500
    });
    contador_tabela = 0;
    conectionID_FK = new Object();
    for(var i in json){
        t = json[i];
        t["show"] = false;
        t["flag"] = contador_tabela;
        conectionID_FK[json[i].id] = contador_tabela;
        copyObject.push(t);
        contador_tabela++;
    }
    json = copyObject;
    for(var i in json)
    {
        if(i == "nomevariaveis")
        {
            continue;
        }
        for(var k in json[i].vs){
            try
            {
                nomecurto = tbObjetoConsulta['nomevariaveis'][json[i].vs[k].iv].nomecurto;
                definicao = tbObjetoConsulta['nomevariaveis'][json[i].vs[k].iv].definicao;
            }
            catch(e){
                addLogErro("build.tablea.js", "469", "473", e);
                continue;
            }
            if(typeof(definicao) == "undefined"){
                definicao = nomecurto;
            }
            
            ano = traduzirAno(json[i].vs[k].ka);
            anos = "";
            
            if(ano == 1991){
                anos = "<k><b>1991</b></k>";
            }else if(ano == 2000){
                anos = "<k><b>2000</b></k>";
            }else if(ano == 2010){
                anos = "<k><b>2010</b></k>";
            }
            $("#staticThead_1").append("<th class='th_hover' tdId='"+k+"'>"+
                "<div id='remove_"+k+"' class=\"customRemoveColumn\" data-original-title='Remover coluna' title data-placement='bottom' onclick=\"removerColuna("+json[i].vs[k].iv+","+json[i].vs[k].ka+",'"+tbObjetoConsulta['nomevariaveis'][json[i].vs[k].iv].sigla+"','"+k+"',"+index+");\"></div>"+
                "<div id='setOrder_"+k+"' class=\"customOrdenarTabela\"  data-original-title='Ordenar' title data-placement='bottom' onclick=\"setOrder(this,"+json[i].vs[k].iv+","+json[i].vs[k].ka+",'"+k+"')\"></div><div style='clear: both'></div>"+
                "<div class='columnTitle' id='columnTitle_"+k+"'>"+
                "<div class='columnTitle_sub'>"+
                "<div class='titleDiv' data-original-title='"+definicao+"' title data-placement='left' id='titleDiv_1_"+k+"'>"+nomecurto+"</div>"+
                "<div class='tableAnoDiv'>"+anos+"</div>"+
                "</div>"+
                "</div>"+
                "</th>");
            $("#titleDiv_1_"+k+"").tooltip({
                delay: 500
            });
            $("#remove_"+k+"").tooltip({
                delay: 500
            });
            $("#setOrder_"+k+"").tooltip({
                delay: 500
            });
            //            $("#staticThead_2").append("<th tdId='"+k+"'>"+
            //            "</th>");
        
            if(nomecurto.length < 35)
                $("#titleDiv_1_"+k).css("font-size",'16px');
            else if(nomecurto.length < 44)
                $("#titleDiv_1_"+k).css("font-size",'12px');
            else if(nomecurto.length < 55)
                $("#titleDiv_1_"+k).css("font-size",'10px');
            else if(nomecurto.length < 75)
                $("#titleDiv_1_"+k).css("font-size",'10px');
            else if(nomecurto.length < 200)
                $("#titleDiv_1_"+k).css("font-size",'9.5px');
            
            index++;
        }
        break;
    }
    $("#tableConsulta_1 tbody").attr("id","tbody_1");
    m = 0;
    $(".titleDiv").each(function(){
        
        if(m < $(this).css("height").replace("px", ""))
            m = $(this).css("height").replace("px", "");
    });
    $(".titleDiv").height(m+"px");
    $("#classUperStatic").height((m - 10)+"px");
    var put = "";
    count = 1;
    nomesSalvo = new Array();
    flag_value_fina = 0;
    for(var i in json){
        if(i == "nomevariaveis" || typeof(json[i].nome) == "undefined")
        {
            continue;
        }
        _j = json[i];
        c = 0;
        if ($("#tableConsulta_1").length == 0){
            tbPagina[i] = 1;
        }
        
        try
        {
            sizeCount = Object.keys(_j).length;
        }catch(e){
            sizeCount = 2;
        }
        p = sizeCount - 2;
        var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
        hold = p;
        id = "trConsulta_1_"+p;
        
        tcity = replaceAllChars(" ", "-", json[i].nome);
        link = "perfil/"+prepara_url((tcity+"_"+json[i].uf)).toLowerCase();
        //        if(isgray)
        //            if(typeof(json[i].uf) != "undefined")
        //                put += "<tr class='hoverTr trGray' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela("+json[i].id+","+json[i].esp+");\"></div><a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+json[i].nome+"','"+json[i].uf+"')\">"+json[i].nome+" ("+json[i].uf+")</div></a></div></td>";
        //            else
        //                put += "<tr class='hoverTr trGray' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+json[i].id+"',"+json[i].esp+");\"></div><div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
        //        else
        rem = "";
        if(typeof(json[i].is_ri) == "undefined")
            rem = "<div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+json[i].id+"',"+json[i].esp+");\"></div>";
        if(typeof(json[i].uf) != "undefined")
            put += "<tr class='hoverTr' i-city='"+json[i].id+"'.tab id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+json[i].nome+"','"+json[i].uf+"')\">"+json[i].nome+" ("+json[i].uf+")</div></a></div></td>";
        else if(typeof(json[i].u) != "undefined")
            put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
        else if(typeof(json[i].country) != "undefined")
            put += "<tr class='hoverTr' style='background:#F0F0F0'  i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
        else if(typeof(json[i].empty) != "undefined")
            put += "<tr class='hoverTr' style='background:#F0F0F0'  i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
        for(var k in json[i].vs){
            v = json[i].vs[k].v == -1 ? "-" : json[i].vs[k].v;
            put += "<td tdId='"+k+"' class='tdValues'>"+v.replace('.',',')+"</td>";
        //                            put += "<td tdId='"+k+"' class='tdValues'>"+json[i].vs[k].v+"</td>";
        }
        put+="</tr>";
        offsetCounter = count;
        count++;
        //        nomesSalvo.push([retira_acentos($.trim(json[i].nome)).toUpperCase(),json[i].id,json[i].nome]);
        //        delete copyObject[i];

        flag_value_fina = i;
        copyObject[i].show = true;
        if(count > 70){
            break;
        }
    }
    $("#tbody_1").html(put);
    cidadesRemovidas = new Array();
    flag_value_fina++;
    if(contador_tabela > 50)
        //        var obtd = $("#tableConsulta_1 tbody")[0];
        //        var tbIn = $("#tabelaPlace")[0];
        $("#second-td-scroll,#tableConsulta_1 tbody").scroll(function() {
            if(flag_value_fina < contador_tabela && 
                (
                    ($("#tbody_1").scrollTop() + $("#tbody_1").height() >= $("#tbody_1").prop('scrollHeight') - 200 && (!$.browser.msie && $.browser.version != "9.0")) ||
                    (
                        ($.browser.msie && $.browser.version == "9.0") && 
                        (
                            $("#second-td-scroll").scrollTop() >= $('#second-td-scroll').prop('scrollHeight') - 650
                            )
                        )
                    )
                && !isLoadingBottom){
                //                alert($("#second-td-scroll").scrollTop()+" >= "+($('#second-td-scroll').prop('scrollHeight') - 650));
                end = contador_tabela;
                if(flag_value_fina+ADD_ON_SCROLL <= contador_tabela)
                    end = flag_value_fina+ADD_ON_SCROLL;
                json = copyObject.slice(flag_value_fina,end);
                var put = "";
                count = 0;
                html = "<tr id='td_loading_1'><td colspan='100%'>Carregando mais linhas <img src='img/map/ajax-loader.gif' /><div></td></tr>";
                $("#tbody_1").append(html);
                isLoadingBottom = true;
                setTimeout(function(){
                    isLoadingBottom = false;
                    for(var i in json){
                        if(i == "nomevariaveis")
                        {
                            continue;
                        }
                        _j = json[i];
                        c = 0;
                        if ($("#tableConsulta_1").length == 0){
                            tbPagina[i] = 1;
                        }

                        try
                        {
                            sizeCount = Object.keys(_j).length;
                        }catch(e){
                            sizeCount = 2;
                        }
                        p = sizeCount - 2;
                        var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
                        hold = p;
                        id = "trConsulta_1_"+p;

                        tcity = replaceAllChars(" ", "-", json[i].nome);
                        link = "perfil/"+prepara_url((tcity+"_"+json[i].uf)).toLowerCase();
                        
                        rem = "";
                        if(typeof(json[i].is_ri) == "undefined"){
                            rem = "<div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+json[i].id+"',"+json[i].esp+");\"></div>";
                        }
                        if(typeof(json[i].uf) != "undefined")
                            put += "<tr class='hoverTr' i-city='"+json[i].id+"'.tab id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+json[i].nome+"','"+json[i].uf+"')\">"+json[i].nome+" ("+json[i].uf+")</div></a></div></td>";
                        else if(typeof(json[i].u) != "undefined")
                            put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
                        else if(typeof(json[i].country) != "undefined")
                            put += "<tr class='hoverTr' style='background:#F0F0F0'  i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
                        for(var k in json[i].vs){
                            v = json[i].vs[k].v == -1 ? "-" : json[i].vs[k].v;
                            put += "<td tdId='"+k+"' class='tdValues'>"+v.replace('.',',')+"</td>";
                        //                            put += "<td tdId='"+k+"' class='tdValues'>"+json[i].vs[k].v+"</td>";
                        }
                        put+="</tr>";
                        flag_value_fina++;
                        copyObject[json[i].flag].show = true;
                        if(count >= ADD_ON_SCROLL){
                            break;
                        }
                        count++;
                    }
            
                    el = $('#tbody_1 tr').last();
                    k = $("#tbody_1").scrollTop() - 350;
                    $("#tbody_1").append(put);
                    $("#td_loading_1").remove();
            
            
                    if(copyObject.length > 0)
                        setTimeout(function(){
                            $("#tbody_1 tr:lt("+REMOVE_ON_SCROLL+")").each(function(){
                                copyObject[flag_value_init].show = false;
                                flag_value_init++;
                                //                        cidadesRemovidas.push($(this).attr("i-city"));
                                $(this).remove();
                            });
                            
                            
                            if($.browser.msie){
                                if($.browser.version == "9.0"){
                                    k = $('#second-td-scroll').scrollTop() - 350;
                                    $('#second-td-scroll').animate({
                                        scrollTop: k
                                    }, 0); 
                                }else{
                                    k = $('#tbody_1 tr').last().offset().top + 200;
                                    $('#tableConsulta_1 tbody').animate({
                                        scrollTop: k
                                    }, 0); 
                                }
                            }else{ 
                                $('#tableConsulta_1 tbody').animate({
                                    scrollTop: k
                                }, 0); 
                            }
                        }, 25);
                    else{
                        if($.browser.msie){
                            if($.browser.version == "9.0"){
                                k = $('#second-td-scroll').scrollTop() - 350;

                                $('#second-td-scroll').animate({
                                    scrollTop: k
                                }, 0); 
                            }else{
                                k = $('#tbody_1 tr').last().offset().top + 200;
                                $('#tableConsulta_1 tbody').animate({
                                    scrollTop: k
                                }, 0); 
                            }
                        }else{ 
                            $('#tableConsulta_1 tbody').animate({
                                scrollTop: k
                            }, 0); 
                        }
                    }
                      
                },TIME_LOADING_ROW);
            }else if(   
                (
                    ($("#tbody_1").scrollTop() < 200 && (!$.browser.msie && $.browser.version != "9.0")) ||
                    (
                        ($.browser.msie && $.browser.version == "9.0") && 
                        (
                            $("#second-td-scroll").scrollTop() < 100
                            )
                        )
                    ) 
                
                && flag_value_init > 0 && !isLoadingTop){
                //            tt = cidadesRemovidas.length - 1;
                //            scroll = $("#tbody_1").scrollTop();
                //            temp = new Array();
                //            counter = 0;
                //            for(t = tt;t >=0;t--){
                //                temp.push(cidadesRemovidas[t]);
                //                cidadesRemovidas.pop();
                //                counter++;
                //                if(counter >= 20){
                //                    break;
                //                }
                //            }
                //            put="";
                //            json = tbObjetoConsulta;
                //            count = 1;
                html = "<tr id='td_loading_2'><td colspan='100%'>Carregando mais linhas <img src='img/map/ajax-loader.gif' /><div></td></tr>";
                $("#tbody_1").prepend(html);
                isLoadingTop = true;
                
                setTimeout(function(){
                    isLoadingTop = false;
                    begin = 0;
                    if(flag_value_init-ADD_ON_SCROLL >= 0)
                        begin = flag_value_init-ADD_ON_SCROLL;
                    json = copyObject.slice(begin,flag_value_init);
                    put = "";
                    for(var i in json){
                        //                       isgray = !isgray;
                        if ($("#tableConsulta_1").length == 0){
                            tbPagina[i] = 1;
                        }

                        try
                        {
                            sizeCount = Object.keys(_j).length;
                        }catch(e){
                            sizeCount = 2;
                        }
                        p = sizeCount - 2;
                        var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
                        hold = p;
                        id = "trConsulta_1_"+p;

                        tcity = replaceAllChars(" ", "-", json[i].nome);
                        link = "perfil/"+prepara_url((tcity+"_"+json[i].uf)).toLowerCase();
                        //                        if(isgray)
                        //                            if(typeof(json[i].uf) != "undefined")
                        //                                put += "<tr class='hoverTr trGray' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela("+json[i].id+","+json[i].esp+");\"></div><a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+json[i].nome+"','"+json[i].uf+"')\">"+json[i].nome+" ("+json[i].uf+")</div></a></div></td>";
                        //                            else
                        //                                put += "<tr class='hoverTr trGray' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+json[i].id+"',"+json[i].esp+");\"></div><div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
                        //                        else
                        
                        
                        rem = "";
                        
                        if(typeof(json[i].is_ri) == "undefined"){
                            rem = "<div id='remove_city_"+json[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+json[i].id+"',"+json[i].esp+");\"></div>";
                        }
                        if(typeof(json[i].uf) != "undefined")
                            put += "<tr class='hoverTr' i-city='"+json[i].id+"'.tab id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+json[i].nome+"','"+json[i].uf+"')\">"+json[i].nome+" ("+json[i].uf+")</div></a></div></td>";
                        else if(typeof(json[i].u) != "undefined")
                            put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
                        else if(typeof(json[i].country) != "undefined")
                            put += "<tr class='hoverTr' style='background:#F0F0F0'  i-city='"+json[i].id+"' id='tr_"+json[i].id+"_"+json[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+json[i].nome+"</div></div></td>";
                        for(var k in json[i].vs){
                            v = json[i].vs[k].v == -1 ? "-" : json[i].vs[k].v;
                            put += "<td tdId='"+k+"' class='tdValues'>"+v.replace('.',',')+"</td>";
                        //                            put += "<td tdId='"+k+"' class='tdValues'>"+json[i].vs[k].v+"</td>";
                        }
                        put+="</tr>";
                        flag_value_init--;
                        count++;
                    }
                    el = $('#tbody_1 tr').first();
                    $("#tbody_1").prepend(put);
            
                    k = 300 - $("#tbody_1").scrollTop();
                    if($.browser.msie){
                        if($.browser.version == "9.0"){
                            k = 400;
                            $('#second-td-scroll').animate({
                                scrollTop: k
                            }, 0); 
                        }else{
                            $('#tableConsulta_1 tbody').animate({
                                scrollTop: $(el).offset().top - k
                            }, 0); 
                        }
                    }else{ 
                        $('#tableConsulta_1 tbody').animate({
                            scrollTop: $(el).offset().top - k
                        }, 0); 
                    }
                    $("#td_loading_2").remove();
                    setTimeout(function(){
                        temp = new Array();
                
                        holder = copyObject;
                        newCopy = new Object();
                        $("#tbody_1 tr").slice(-REMOVE_ON_SCROLL).each(function(){
                            flag_value_fina--;
                            copyObject[flag_value_fina].show = false;
                            //                    newCopy[$(this).attr("i-city")] = tbObjetoConsulta[$(this).attr("i-city")];
                            //                    temp.push(tbObjetoConsulta[$(this).attr("i-city")]);
                            $(this).remove();
                        });
                    //                for(var i in holder){
                    //                    newCopy[i] = holder[i];
                    //                }
                    //                copyObject = newCopy;
                    }, 25);
                },TIME_LOADING_ROW);
            }
        });
    
    
    for(var i in tbObjetoConsulta){
        
        if(i == "nomevariaveis")
        {
            continue;
        }
        nomesSalvo.push([retira_acentos($.trim(tbObjetoConsulta[i].nome)).toUpperCase(),tbObjetoConsulta[i].id,tbObjetoConsulta[i].nome]);
    }
    
    pesquisaPopOverBuilder();
    
    //    $(".hoverTr").hover(
    //        function(){
    //            $(this).children("td").each(function(){
    //                $(this).css("background", "#d9edf7");
    //            });
    //        },function(){
    //            if($(this).attr("class") == "hoverTr")
    //                $(this).children("td").each(function(){
    //                    $(this).css("background", "#fff");
    //                });
    //            else
    //                $(this).children("td").each(function(){
    //                    $(this).css("background", "#EFEFEF");
    //                });
    //        });
    
    if(typeof(tabelaImprimir) != "undefined")
    {
        if(tabelaImprimir){
            window.print();
        }
    }else{
        if(!holdLoading)
            loadingHolder.dispose();
    }
    if(getCountIndicadores() < 20)
        newTdBlank();
//nomesSalvo.sort(sort_by(0, false, function(a){return retira_acentos(a.toUpperCase())}));
}

function gotoPerfil(city,uf){
    location.href("perfil/"+result);
}

function toUrlName(name){
    while(name.indexOf(" ") != -1){
        name = name.replace(" ","_");
    }
    name = retira_acentos(name);
    name = name.toLowerCase();
    return name;
}

function removerLinhaTabela(k,l){
    $("#tr_"+k+"_"+l).remove();
    delete tbObjetoConsulta[k];
    copyObject.splice((conectionID_FK[k]), 1);
    k = k.toString().replace("e", "");
    geral.removeLugar(l, k);
    flag_value_fina--;
    json = copyObject;
    copyObject = new Array()
    conectionID_FK = new Object();
    fg = 0;
    for(var i in json){
        t = json[i];
        t["show"] = false;
        t["flag"] = fg;
        conectionID_FK[json[i].id] = fg;
        copyObject.push(t);
        fg++;
    }
    contador_tabela --;
    put = "";
    if(flag_value_fina < contador_tabela){
        var i = flag_value_fina;
        try
        {
            sizeCount = Object.keys(_j).length;
        }catch(e){
            sizeCount = 2;
        }
        p = sizeCount - 2;
        var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
        hold = p;
        id = "trConsulta_1_"+p;

        tcity = replaceAllChars(" ", "-", copyObject[i].nome);
        link = "perfil/"+prepara_url((tcity+"_"+copyObject[i].uf)).toLowerCase();
        rem = "";
        if(typeof(copyObject[i].is_ri) == "undefined")
            rem = "<div id='remove_city_"+copyObject[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+copyObject[i].id+"',"+copyObject[i].esp+");\"></div>";
        if(typeof(copyObject[i].uf) != "undefined")
            put += "<tr class='hoverTr' i-city='"+copyObject[i].id+"'.tab id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+copyObject[i].nome+"','"+copyObject[i].uf+"')\">"+copyObject[i].nome+" ("+copyObject[i].uf+")</div></a></div></td>";
        else if(typeof(copyObject[i].u) != "undefined")
            put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+copyObject[i].id+"' id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[i].nome+"</div></div></td>";
        else if(typeof(copyObject[i].country) != "undefined")
            put += "<tr class='hoverTr' style='background:#F0F0F0' i-city='"+copyObject[i].id+"' id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[i].nome+"</div></div></td>";

        for(var k in copyObject[i].vs){
            put += "<td tdId='"+k+"' class='tdValues'>"+copyObject[i].vs[k].v.replace('.',',')+"</td>";
        }
        put+="</tr>";
        flag_value_fina++;
        copyObject[copyObject[i].flag].show = true
        $("#tbody_1").append(put);
    }else if(flag_value_init > 0){
        
        var i = flag_value_init;
        try
        {
            sizeCount = Object.keys(_j).length;
        }catch(e){
            sizeCount = 2;
        }
        p = sizeCount - 2;
        var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
        hold = p;
        id = "trConsulta_1_"+p;

        tcity = replaceAllChars(" ", "-", copyObject[i].nome);
        link = "perfil/"+prepara_url((tcity+"_"+copyObject[i].uf)).toLowerCase();
        rem = "";
        if(typeof(copyObject[i].is_ri) == "undefined")
            rem = "<div id='remove_city_"+copyObject[i].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+copyObject[i].id+"',"+copyObject[i].esp+");\"></div>";
        if(typeof(copyObject[i].uf) != "undefined")
            put += "<tr class='hoverTr' i-city='"+copyObject[i].id+"'.tab id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+copyObject[i].nome+"','"+copyObject[i].uf+"')\">"+copyObject[i].nome+" ("+copyObject[i].uf+")</div></a></div></td>";
        else if(typeof(copyObject[i].u) != "undefined")
            put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+copyObject[i].id+"' id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[i].nome+"</div></div></td>";
        else if(typeof(copyObject[i].country) != "undefined")
            put += "<tr class='hoverTr' style='background:#F0F0F0' i-city='"+copyObject[i].id+"' id='tr_"+copyObject[i].id+"_"+copyObject[i].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[i].nome+"</div></div></td>";

        for(var k in copyObject[i].vs){
            put += "<td tdId='"+k+"' class='tdValues'>"+copyObject[i].vs[k].v.replace('.',',')+"</td>";
        }
        put+="</tr>";
        flag_value_init--;
        copyObject[copyObject[i].flag].show = true
        $("#tbody_1").prepend(put);
    }
}

function pesquisaPopOverBuilder() {
    $("#area_btnPesquisaTabela").popover({
        trigger:'manual',
        content: function() {
            return $('#Conten_btnPesquisaTabela').html();
        }, 
        html:true
    });
    
    
    $("#btnPesquisaTabela").focusout(function(){
        $("#area_"+$(this).attr("id")).popover('hide');
    });
    
    $("#btnPesquisaTabela").focus(function(){
        if($(this).html() == "") return;
        $("#area_"+$(this).attr("id")).popover('show');
    });
    
    
    $("#btnPesquisaTabela").keydown(function(event){
        if(event.keyCode == 40){
            if($("#area_inpt_btnPesquisaTabela .searchFocused").length){
                last = $("#area_inpt_btnPesquisaTabela .searchFocused");
                $("#area_inpt_btnPesquisaTabela .searchFocused").next("#area_inpt_btnPesquisaTabela [id-m='btnPesquisaTabela']").addClass("searchFocused");
                $(last).removeClass("searchFocused");
            }else{
                $("#area_inpt_btnPesquisaTabela [id-m='btnPesquisaTabela']").first().addClass("searchFocused");
            }
            return;
        }else
        if(event.keyCode == 38){
            if($("#area_inpt_btnPesquisaTabela .searchFocused").length){
                last = $("#area_inpt_btnPesquisaTabela .searchFocused");
                $("#area_inpt_btnPesquisaTabela .searchFocused").prev("#area_inpt_btnPesquisaTabela [id-m='btnPesquisaTabela']").addClass("searchFocused");
                $(last).removeClass("searchFocused");
            }else{
                $("#area_inpt_btnPesquisaTabela [id-m='btnPesquisaTabela']").last().addClass("searchFocused");
            }
            return;
        }
        else if(event.keyCode == 13){
            if($.trim($(this).val()) == ''){
                return;
            }
            if($("#area_inpt_btnPesquisaTabela .searchFocused").length){
                $(this).val($("#area_inpt_btnPesquisaTabela .searchFocused").html());
                $(this).attr('i',$("#area_inpt_btnPesquisaTabela .searchFocused").attr('i'));
                $("#area_"+$(this).attr("id")).popover('hide');
                //storedText[$(this).attr("id")] = $(this).val();
                //$(this).change();
                navegarPara((this));
            }
            if($("#btnPesquisaTabela").attr("i") != "0"){
                navegarPara((this));
            }
            return;
        }
    });
    $("#btnPesquisaTabela").keyup(function(e){
        cod = e.keyCode;
        if(
            cod ==  38 ||
            cod ==  13 ||
            cod ==  40
                )return;
        clearInterval(timer);
        timer = window.setTimeout(function(atual){
            atual = $("#btnPesquisaTabela");
            if($.trim($(atual).val()) == ""){
                $('#Conten_btnPesquisaTabela').html("");
                return;
            }
            arrayDeBase = new Array();
            pivot = Math.round(nomesSalvo.length /2);
            init = 0;
            end = 0;
            if(pivot > 0){
                init = pivot-1;
                end = pivot;
                var regex = new RegExp("^" + $.trim(  retira_acentos($("#btnPesquisaTabela").val())  ).toUpperCase());
                for(;init >= 0 || end < nomesSalvo.length;init--,end++){
                    if(typeof(nomesSalvo[init]) != "undefined" && regex.test($.trim(nomesSalvo[init][0]))){
                        arrayDeBase.push([nomesSalvo[init][2],nomesSalvo[init][1]]);
                    }
                    if(typeof(nomesSalvo[end]) != "undefined" && regex.test($.trim(nomesSalvo[end][0]))){
                        arrayDeBase.push([nomesSalvo[end][2],nomesSalvo[end][1]]);
                    }
                }
                init = pivot-1;
                arrayDeBase.sort(sort_by(0, true, function(a){
                    return a.toUpperCase()
                }));
                arrayPart2 = new Array();
                end = pivot;
                for(;init > 0 || end < nomesSalvo.length;init--,end++){
                    if(!regex.test($.trim(nomesSalvo[init][0])) && $.trim(nomesSalvo[init][0]).indexOf($.trim($("#btnPesquisaTabela").val()),0) !== -1){
                        arrayPart2.push([nomesSalvo[init][2],nomesSalvo[init][1]]);
                    }
                    if(!regex.test($.trim(nomesSalvo[end][0].toUpperCase())) && $.trim(nomesSalvo[end][0]).indexOf($.trim($("#btnPesquisaTabela").val()),0) !== -1){
                        arrayPart2.push([nomesSalvo[end][2],nomesSalvo[end][1]]);
                    }
                }
                arrayPart2.sort(sort_by(0, true, function(a){
                    return a.toUpperCase()
                }));
                divAdd = "";
                arrayDeBase = arrayDeBase.concat(arrayPart2);
                k = arrayDeBase.length > 10 ? 10 : arrayDeBase.length;
                if(k == 0){
                    divAdd = "Nenhum resultado encontrado.";
                }else
                    for(x = 0; x < k; x++){
                        if(typeof(tbObjetoConsulta[arrayDeBase[x][1]]) != "undefined"){
                            if(typeof(tbObjetoConsulta[arrayDeBase[x][1]].uf) != "undefined")
                                divAdd = divAdd + "<div i='"+arrayDeBase[x][1]+"' id-m='"+atual.attr("id")+"' class='divResultsPopUp' onclick=\"navegarPara(this)\">"+arrayDeBase[x][0]+" ("+tbObjetoConsulta[arrayDeBase[x][1]].uf+")</div>";
                            else
                                divAdd = divAdd + "<div i='"+arrayDeBase[x][1]+"' id-m='"+atual.attr("id")+"' class='divResultsPopUp' onclick=\"navegarPara(this)\">"+arrayDeBase[x][0]+"</div>";
                        }
                    }
                if(arrayDeBase.length > 0){
                    $("#btnPesquisaTabela").attr('i',arrayDeBase[0][1]);
                }
                $('#Conten_btnPesquisaTabela').html(divAdd);
                $("#area_btnPesquisaTabela").popover('show');
            }
        },500,$(this));
    });
}

var el_g;

function navegarPara(element){
    el_g = element;
    var sv;
    for(var i in copyObject){
        if(copyObject[i].id == $(element).attr("i")){
            sv = copyObject[i];
            break;
        }
    }
    //    if(!$("[i-city='"+$(element).attr("i")+"']").length)
    //        return;
    //    if($("[i-city='"+$(element).attr("i")+"']").css("display") == "none"){
    ////        loadMoreForce(parseInt($("[i-city='"+$(element).attr("i")+"']").attr("counter")),element);
    ////        return;
    //    }
    if(copyObject.length < 70){
        k = 600 - $("#tbody_1").scrollTop();
        if($.browser.msie){
            if($.browser.version == "9.0"){
                k = 400;
                $('html, body').animate({
                    scrollTop: $("[i-city='"+$(element).attr("i")+"']").offset().top - k
                }, 1000); 
            }else{
                $('#tableConsulta_1 tbody').animate({
                    scrollTop: $("[i-city='"+$(element).attr("i")+"']").offset().top - k
                }, 1000); 
            }
        }else{ 
            $('#tableConsulta_1 tbody').animate({
                scrollTop: $("[i-city='"+$(element).attr("i")+"']").offset().top - k
            }, 1000); 
        }
        $("[i-city='"+$(element).attr("i")+"'] td").addClass("tableFocused");
        $(element).blur(); 
        $("#area_btnPesquisaTabela").clickover("hide");
        setTimeout(function(){
            $(".tableFocused").removeClass("tableFocused");
        }
        ,4000);
    }else{
        var isgray = false;
        start = sv.flag - 25;
        end = sv.flag + 25;
        goto_t = 0;
        if(start < 0){
            goto_t = -1;
            temp = start;
            start = 0;
            temp = (temp*-1);
            end = end + temp;
            if(end > contador_tabela - 1){
                end = contador_tabela - 1;
            }
        }
        if(end > contador_tabela - 1){
            goto_t = 1;
            temp = end;
            end = contador_tabela - 1;
            temp = temp - end;
            start = start - temp;
            start++;
            if(start < 0)
                start = 0;
        }
        put = "";
        flag_value_fina = end;
        flag_value_init = start;
        while(start < end){
            if ($("#tableConsulta_1").length == 0){
                tbPagina[i] = 1;
            }

            try
            {
                sizeCount = Object.keys(_j).length;
            }catch(e){
                sizeCount = 2;
            }
            p = sizeCount - 2;
            var html = "<div style='width:200px'><div>Exibir na árvore do IDH</div><div>Exibir no gráfico</div><div>Ir para o perfil</div></div>";
            hold = p;
            id = "trConsulta_1_"+p;

            tcity = replaceAllChars(" ", "-", copyObject[start].nome);
            link = "perfil/"+prepara_url((tcity+"_"+copyObject[start].uf)).toLowerCase();
            //            if(isgray)
            //                if(typeof(copyObject[start].uf) != "undefined")
            //                    put += "<tr class='hoverTr trGray' i-city='"+copyObject[start].id+"' id='tr_"+copyObject[start].id+"_"+copyObject[start].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+copyObject[start].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela("+copyObject[start].id+","+copyObject[start].esp+");\"></div><a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+copyObject[start].nome+"','"+copyObject[start].uf+"')\">"+copyObject[start].nome+" ("+copyObject[start].uf+")</div></a></div></td>";
            //                else
            //                    put += "<tr class='hoverTr trGray' i-city='"+copyObject[start].id+"' id='tr_"+copyObject[start].id+"_"+copyObject[start].esp+"'><td class='tdNames th_2'><div class='enlarger'><div id='remove_city_"+copyObject[start].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+copyObject[start].id+"',"+copyObject[start].esp+");\"></div><div class='tdNames textNameTable'>"+copyObject[start].nome+"</div></div></td>";
            //            else
            rem = "";
            if(typeof(copyObject[start].is_ri) == "undefined")
                rem = "<div id='remove_city_"+copyObject[start].id+"' class=\"customRemoveColumn2\" onclick=\"removerLinhaTabela('"+copyObject[start].id+"',"+copyObject[start].esp+");\"></div>";
        
            if(typeof(copyObject[start].uf) != "undefined")
                put += "<tr class='hoverTr' i-city='"+copyObject[start].id+"'.tab id='tr_"+copyObject[start].id+"_"+copyObject[start].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<a href='"+link+"' target=\"_blank\"><div class='tdNames textNameTable' data-original-title='Ver no perfil' title data-placement='right' onclick=\"gotoPerfil('"+copyObject[start].nome+"','"+copyObject[start].uf+"')\">"+copyObject[start].nome+" ("+copyObject[start].uf+")</div></a></div></td>";
            else if(typeof(copyObject[start].u) != "undefined")
                put += "<tr class='hoverTr' style='background:#F8F8F8' i-city='"+copyObject[start].id+"' id='tr_"+copyObject[start].id+"_"+copyObject[start].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[start].nome+"</div></div></td>";
            else if(typeof(copyObject[start].country) != "undefined")
                put += "<tr class='hoverTr' style='background:#F0F0F0' i-city='"+copyObject[start].id+"' id='tr_"+copyObject[start].id+"_"+copyObject[start].esp+"'><td class='tdNames th_2'><div class='enlarger'>"+rem+"<div class='tdNames textNameTable'>"+copyObject[start].nome+"</div></div></td>";

            for(var k in copyObject[start].vs){
                v = copyObject[start].vs[k].v == -1 ? '-' : copyObject[start].vs[k].v;
                put += "<td tdId='"+k+"' class='tdValues'>"+v.replace('.',',')+"</td>";
            }
            put+="</tr>";
            count++;
            start++;
        }
        $("#tbody_1").html(put);
        $("[i-city='"+$(element).attr("i")+"'] td").addClass("tableFocused");
        //        alert(h);
        //        $("#tbody_1").scrollTo(0,600);
        h = ($("#tbody_1")[0].scrollHeight/2)-250;
        if(goto_t == 1)
            h = ($("#tbody_1")[0].scrollHeight);
        else if(goto_t == -1)
            h = 0;
        
        $('#tableConsulta_1 tbody').animate({
            scrollTop: h
        }, 0); 
        setTimeout(function(){
            $(".tableFocused").removeClass("tableFocused");
        }
        ,4000);
        k = 0;
    //        if($.browser.msie){
    //            if($.browser.version == "9.0"){
    //                k = 400;
    //                $('html, body').animate({
    //                    scrollTop: $("#tr_"+sv.id+"_"+sv.esp).offset().top - k
    //                }, 1000); 
    //            }else{
    //                $('#tableConsulta_1 tbody').animate({
    //                    scrollTop: $("#tr_"+sv.id+"_"+sv.esp).offset().top - k
    //                }, 1000); 
    //            }
    //        }else{ 
    //            $('#tableConsulta_1 tbody').animate({
    //                scrollTop: $("#tr_"+sv.id+"_"+sv.esp).offset().top - k
    //            }, 1000); 
    //        }
    //        $(el_g).blur(); 
    //        $("#area_btnPesquisaTabela").clickover("hide");
    }
}

function limparTodosIndices(){
    if(geral.getIndicadores().length > 0){
        loadingHolder.show("Limpando tabela...");
        geral.removerIndicadoresTodos();
        for(var i in tbObjetoConsulta){
            tbObjetoConsulta[i].vs = new Array();
        }
        fillTabela(tbObjetoConsulta);
        loadingHolder.dispose();
    }
}

function limparTodasLinhasTabela(){
    geral.removeLugarTodos();
    map_indc2.refresh();
    vars = tbObjetoConsulta["nomevariaveis"];
    tbObjetoConsulta = new RegExp();
    tbObjetoConsulta["nomevariaveis"] = vars;
    
    fillEnptyTabela();
}

function newTdBlank(){
    return;
    $("#staticThead_1").append("<th class='borderRight' w-id='0' id='nodeValues'>"+
        "<div class='columnTitle'>"+
        "<div id=\"tabelaSelectorIndcadorSingle\">"+
        "<div class=\"divCallOut\">"+
        "<button class=\"btn dropdown selector_popover styleBtn1\" data-toggle=\"dropdown\" rel=\"popover\" >+</button>"+
        "</div>"+
        "</div>"+
        "</div>"+
        "</th>");
    $("#tableConsulta_1 tr").each(function(){
        $(this).append("<td w-id='"+$(this).attr("i-city")+"' class='tdValues'></td>");
    });
    $("#staticThead_2").append("<th class='' w-id='-1' ></th>");
    try{
        var tbSelectorIndcadorSingle = new IndicatorSelector();
        tbSelectorIndcadorSingle.startSelector(false,"tabelaSelectorIndcadorSingle",listnerSelectorIndcadorSingle,"bottom");
    }catch(e){
    //erro
    }
    function listnerSelectorIndcadorSingle(array)
    {
        loadingHolder.show("Carregando dados...");
        var indicador = new IndicadorPorAno();
        indicador.id = array[0].id;
        indicador.nc = array[0].nc;
        indicador.a = 3;
        indicador.desc = array[0].desc;
        indicador.sigla = array[0].sigla;
        indc = geral.getIndicadores();
        if(geral.getLugares().length == 0){
            geral.addIndicador(indicador);
            loadingHolder.dispose();
            fillEnptyTabela();
            return;
        }
        for(var u in indc){
            if(indc[u].id == array[0].id){
                addNewColumnValuesVirtual(array[0].sigla,3,array[0].nc,array[0].id);
                geral.addIndicador(indicador);
                return;
            }
        }
        geral.addIndicador(indicador);
        addNewColumnValues(array[0].sigla,3,array[0].nc,0,array[0].id);
    }
}

function getEstados(){
    $.getJSON('com/mobiliti/preconsultas/estados-uf-nome.js', function(data) {
        jsonEstados = data;
    });
}
