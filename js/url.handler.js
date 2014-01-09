/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
    var UrlHandler = function(BaseUrl,PaginaInicial) {
        this.url = document.URL;
        this.urlBase = BaseUrl;
        this.urlPaginaAtual = PaginaInicial;
        this.urlMapa;
        this.urlTabela;
        this.urlHistograma;
        this.urlLast;
        this.resetUrl = function() {
            this.url = document.URL.replace(this.urlBase+"atlas/","");
            k = this.url.split("/");
            this.urlPaginaAtual = k[0];
            k.shift();
            this.url = k.join("/");
            
            tempUrl = this.url.split("/mapa/");
            
            this.urlTabela = tempUrl[0].replace("tabela/", "");
            
            sub = tempUrl[1].split("/histograma/");
            this.urlMapa = sub[0].replace("/mapa/", "");
            if(typeof sub[1] == "undefined" || this.urlHistograma == "nulo"){
                this.urlHistograma = "nulo";
                return;
            }
            this.urlHistograma = sub[1];
        }
        
        this.verificarBarrasDuplas = function(){
            temp = this.url.split("/");
            for(i = 0; i < temp.length; i++){
                if(temp[i] == ""){
                    temp.splice(i, 1);
                    i--;
                }
            }
            this.url = temp.join("/");
        }
        this.setarNulo = function(){
            temp = document.URL.replace(this.urlBase+"atlas/","");
            k = temp.split("/");
            k.shift();
            ArrayTabela = new Array();
            ArrayMapa = new Array();
            ArrayHistograma = new Array();
            if(k.indexOf("tabela") == -1){
                this.urlTabela = "tabela/nulo"
            }else{
                tempK = k.indexOf("tabela");
                for(i = tempK; i < k.length; i++){
                    if(k[i] != 'mapa' && k[i] != 'histograma'){
                        if(k[i] == 'tabela' && ArrayTabela.indexOf("tabela") != -1)
                            break;
                        ArrayTabela.push(k[i]);
                    }else{
                        break;
                    }
                }
                if(typeof ArrayTabela != "undefined" && ArrayTabela.length > 2)
                    this.urlTabela = ArrayTabela.join("/");
                else
                    this.urlTabela = "tabela/nulo";
            }
            if(k.indexOf("mapa") == -1){
                this.urlMapa = "mapa/nulo"
            }else{
                for(i = k.indexOf("mapa"); i < k.length; i++){
                    if(k[i] != 'tabela' && k[i] != 'histograma'){
                        if(k[i] == 'mapa' && ArrayMapa.indexOf("mapa") != -1)
                            break;
                        ArrayMapa.push(k[i]);
                    }else{
                        break;
                    }
                }
                if(typeof ArrayMapa != "undefined" && ArrayMapa.length > 2)
                    this.urlMapa = ArrayMapa.join("/");
                else
                    this.urlMapa = "mapa/nulo";
            }
            if(k.indexOf("histograma") == -1){
                this.urlHistograma = "histograma/nulo"
            }else{
                for(i = k.indexOf("histograma"); i < k.length; i++){
                    if(k[i] != 'tabela' && k[i] != 'mapa'){
                        if(k[i] == 'histograma' && ArrayHistograma.indexOf("histograma") != -1)
                            break;
                        ArrayHistograma.push(k[i]);
                    }else{
                        break;
                    }
                }
                if(typeof ArrayHistograma != "undefined" && ArrayHistograma.length > 2)
                    this.urlHistograma = ArrayHistograma.join("/");
                else
                    this.urlHistograma = "histograma/nulo"
            }
            this.url = 'atlas/'+this.urlPaginaAtual+"/"+this.urlTabela+"/"+this.urlMapa+"/"+this.urlHistograma;
            this.verificarBarrasDuplas();
            history.pushState( "", "Atlas Fase 3 ", this.url);
        }
        //http://localhost/atlas3/atlas/tabela/tabela/municipal/filtro/brasil/indicador/idhm-2000,espvida-2010,PTER25M-2000,PTER25M-2010,P20ME-1991,CARRO-2000/mapa/municipal/filtro/regiao/sudeste,norte,nordeste/filtro/estado/minas_gerais,sao_paulo,rio_grande_do_sul/indicador/idhm-2010
        this.getConsulta = function(){
           
        }
        
        this.exportConsulta = function(to,url){
            
        }
        
        this.formatTabelaToExport = function(indicador){
            formatada = this.urlTabela.split("indicador");
            return formatada[0] + "indicador/"+indicador;
        }
        
        this.getUrl = function(consulta){
            this.resetUrl();
            str = "";
            switch(consulta.toLowerCase()){
                case 'tabela':
                    str = this.urlTabela;
                    break;
                case 'mapa':
                    str = this.urlMapa;
                    break;
                case 'histograma':
                    str = this.urlHistograma;
                    break;
            }
            if(typeof str != 'undefined')
                return str;
            return null;
        }
        
        this.getBase = function(){
            return this.urlBase;
        }
        
        this.setPaginaAtual = function(stringPagina){
            url = this.getUrl(stringPagina);
            if(typeof url == 'undefined' || url == "nulo")
            {
                return;
            }
            this.urlPaginaAtual = stringPagina;
            this.resetUrl();
            this.setUrl(stringPagina,url);
        }
        
        this.getPaginaAtual = function(){
            return this.urlPaginaAtual;
        }
        
        this.setUrl = function(consulta,url){
            if(typeof url == 'undefined' || typeof consulta == 'undefined' || url == "nulo"){
                return;
}
            switch(consulta.toLowerCase()){
                case 'tabela':
                    tempUrl = "atlas/tabela/tabela/"+url+"/mapa/"+this.urlMapa+"/histograma/"+this.urlHistograma;
                    break;
                case 'mapa':
                    tempUrl = "atlas/mapa/tabela/"+this.urlTabela+"/mapa/"+url+"/histograma/"+this.urlHistograma;
                    break;
                case 'histograma':
                    tempUrl = "atlas/histograma/tabela/"+this.urlTabela+"/mapa/"+this.urlMapa+"/histograma/"+url;
                    break;
            }
            
            if(this.urlLast != tempUrl){
                this.urlLast = tempUrl;
                history.pushState( "", "Atlas Fase 3 ", tempUrl);
            }else{
                return;
            }
        }
        
    };

    var OldUrl = new RegExp();
    OldUrl['tabela'] = "";
    OldUrl['mapa'] = "";
    OldUrl['histograma'] = "";
    $(document).ready(function(){
            $("body").bind('atualizarAbas', function(e, aba,obj) {
                switch(aba){
                    case 'tabela':
                        if(OldUrl["tabela"] != obj.getUrl("tabela")){
                            JSONSalvo = new RegExp();
                            tabela_build(obj.getUrl("tabela"),1,0,0,0);
                        }
                        OldUrl["tabela"] = obj.getUrl("tabela");
                        break;
                    case 'mapa':
                        if(OldUrl["mapa"] != obj.getUrl("mapa"))
//                            map_build("/"+obj.getUrl("mapa")+"/");
//                        OldUrl["mapa"] = obj.getUrl("mapa");
                        break;
                    case 'histograma':
                        if(OldUrl["histograma"] != obj.getUrl("histograma"))
                            histogram_build(obj.getUrl("histograma"));
                        OldUrl["histograma"] = obj.getUrl("histograma");
                        break;
                }
            }); 
        
    });
*/
