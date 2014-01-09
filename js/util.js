/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function converterEspacialidadeParaId(string){
    switch(string){
        case 'municipal':
            return 2;
        case 'regioal':
            return 3;
        case 'estadual':
            return 4;
        case 'udh':
            return 5;
        case 'regiaometropolitana':
            return 6;
        case 'regiaointeresse':
            return 7;
        case 'mesorregiao':
            return 8;
        case 'microrregiao':
            return 9;
        case 'pais':
            return 10;
    }
}

function getValorUrl(url,campo){
    //getValorUrl(UrlController.getUrl('tabela'),"regiao");
    explode  = url.split("/");
    k = explode.indexOf(campo);
    if(k == -1){
        return null;
    }else if(typeof(explode[k+1]) ==  "undefined"){
        return null;
    }else{
        return (explode[k+1]);
    }
}

function addValorUrl(suaUrl,campo,valor){
    //UrlController.setUrl('tabela',addValorUrl(UrlController.getUrl('tabela'), "indicador","idhm-3000"));
    explode  = suaUrl.split("/");
    k = explode.indexOf(campo);
    if(k == -1){
        return null;
    }else if(typeof(explode[k+1]) ==  "undefined"){
        return null;
    }else{
        campos = new Array();
        campos = explode[k+1].split(',');
        if(true){
            if(campos[0] == "")
                campos = valor;
            else{
                campos.push(valor);
                campos = campos.join(',');
            }
            explode[k+1] = campos;
            explode = explode.join("/");
        }else{
            return explode.join("/");
        }
        return explode;
    }
}

function setarValorUrl(suaUrl,campo,valor){
    
    explode  = suaUrl.split("/");
    k = explode.indexOf(campo);
    if(k == -1){
        return null;
    }else if(typeof(explode[k+1]) ==  "undefined"){
        return null;
    }else{
        explode[k+1] = valor;
        return explode.join("/");
    }
}

function converterEspacialidadeParaString(id){
    switch(id){
        case 2:
            return 'municipal';
        case 3:
            return "regional";
        case 4:
            return 'estadual';
        case 5:
            return 'udh';
        case 6:
            return 'regiaometropolitana';
        case 7:
            return 'regiaointeresse';
        case 8:
            return 'mesorregiao';
        case 9:
            return 'microrregiao';
        case 19:
            return 'pais';
    }
}

function converterEspacialidadeParaStringExtenso(id){
    switch(id){
        case 2:
            return 'Municípal';
        case 3:
            return "Regional";
        case 4:
            return 'Estadual';
        case 5:
            return 'UDH';
        case 6:
            return 'Região Metropolitana';
        case 7:
            return 'Região de Interesse';
        case 8:
            return 'Mesorregião';
        case 9:
            return 'Microrregião';
        case 19:
            return 'País';
    }
}

function getEspaciliadadeUrl(url){
    if(url.indexOf("municipal") >= 0)
        return 2;
    else if(url.indexOf('regional') >= 0)
        return 3;
    else if(url.indexOf('estadual') >= 0)
        return 4;
    else if(url.indexOf('udh') >= 0)
        return 5;
    else if(url.indexOf('regiaometropolitana') >= 0)
        return 6;
    else if(url.indexOf('regiaointeresse') >= 0)
        return 7;
    else if(url.indexOf('mesorregiao') >= 0)
        return 8;
    else if(url.indexOf('microrregiao') >= 0)
        return 9;
    else if(url.indexOf('pais') >= 0)
        return 10;
}

function changeEspacializacao(url,newEspacializacao){
    exp = url.split("/");
    x = 0;
    if(exp.indexOf("municipal") >= 0)
        x = exp.indexOf("municipal");
    else if(exp.indexOf('regioal') >= 0)
        x = exp.indexOf("regioal");
    else if(exp.indexOf('estadual') >= 0)
        x = exp.indexOf("estadual");
    else if(exp.indexOf('udh') >= 0)
        x = exp.indexOf("udh");
    else if(exp.indexOf('regiaometropolitana') >= 0)
        x = exp.indexOf("regiaometropolitana");
    else if(exp.indexOf('regiaointeresse') >= 0)
        x = exp.indexOf("regiaointeresse");
    else if(exp.indexOf('mesorregiao') >= 0)
        x = exp.indexOf("mesorregiao");
    else if(exp.indexOf('microrregiao') >= 0)
        x = exp.indexOf("microrregiao");
    else if(exp.indexOf('pais') >= 0)
        x = exp.indexOf("pais");
    exp[x] = converterEspacialidadeParaString(parseInt(newEspacializacao));
    return exp.join('/');
}

function retira_acentos(palavra) {
    com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ';
    sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC';
    nova='';
    intI = palavra.length;
    //    for(i=0;i<palavra.length;i++) {
    //      if (com_acento.search(palavra.substr(i,1))>=0) {
    //      nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1);
    //      }
    //      else {
    //       nova+=palavra.substr(i,1);
    //      }
    //    }
    intI--;
    do{
        if (com_acento.search(palavra.substr(intI,1))>=0) {
            nova=sem_acento.substr(com_acento.search(palavra.substr(intI,1)),1) + nova;
        }
        else {
            nova=palavra.substr(intI,1) + nova;
        }
    }while(intI--);
    return nova;
}

function prepara_url(palavra){
    return retira_acentos(palavra).replace("\'",'');
}

function convertAno(anoLabel){
    switch(anoLabel){
        case 1991:
            return 1;
        case 2000:
            return 2;
        case 2010:
            return 3
        case '1991':
            return 1;
        case '2000':
            return 2;
        case '2010':
            return 3
    }
}
function convertAnoIDtoLabel(anoLabel){
    switch(anoLabel){
        case 1:
            return 1991;
        case 2:
            return 2000;
        case 3:
            return 2010;
    }
}
function sleep(ms)
{
    var dt = new Date();
    dt.setTime(dt.getTime() + ms);
    while (new Date().getTime() < dt.getTime());
}

function replaceAllChars(find,replace,text){
    try
    {
        while(text.indexOf(find) != -1){
            text = text.replace(find,replace);
        }
        return text;
    }catch(e){
        return null;
    }
}


function addLogErro(file,linha_inicial, linha_final, e){
    $.ajax({
        type: 'post',
        url:'com/mobiliti/util/AjaxLogErro.php',
        data:{
            'file':file,
            'linha_inicial' : linha_inicial,
            'linha_final' : linha_final,
            'e' : e,
            'navegador' : "n="+navigator.appName + " v="+navigator.appVersion
        }
    });
}

function exportGeral(){
    L = JSON.stringify(geral.getLugares());
    L[0] = "";
    L[L.length - 1] = "";
    I = JSON.stringify(geral.getIndicadores());
    I[0] = "";
    I[I.length - 1] = "";
    $("body").html("["+L+","+I+"]");
    
}

function deb(text){
}




//mapa aparece checado
//alinhar anos
//setar o slide com o primero ano do indicador


