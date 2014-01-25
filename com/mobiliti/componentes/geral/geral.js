function Geral(listenerReady){
//    console.log('============');
//    console.log('GERAL');
    
    lugares = new Array();
    indicadores = new Array();
    var eixo;

    listenerLugares = null;
    listenerIndicadores = null;
        
    var areas_tematicas = new Array();
    
    var ready = listenerReady;

    this.listenerReady = function(value){
        console.log('Geral - listenerReady');
        ready = value;
    }

    this.dispatchListeners = function(event){
        console.log('Geral - dispatchListeners');
        //console.log('listenerLugares: '+listenerLugares);
        listenerIndicadores(event, indicadores);
        listenerLugares(event, lugares);
    }
    
    this.setEixo = function(eixo_){
        console.log('Geral - setEixo');
//        console.log('eixo_: '+eixo_);
        if(eixo_ == 'y'){
            eixo = 0;
        }
        else if(eixo_ == 'x'){
            eixo = 1;
        }
        else if(eixo_ == 'tam'){
            eixo = 2;
        }
        else if(eixo_ == 'cor'){
            eixo = 3;
        } 
    }
    
    this.getEixo = function(){ 
        console.log('Geral - getEixo');
        return eixo;
    }

    /**
    * Retorna a posição do elemento adicionado
    * */
    this.addLugar = function(value){
        console.log('Geral - addLugar');
        var obj = lugares.push(value);
        if(listenerLugares)
            listenerLugares('check',obj);
        else
            alert('listener não esta definido!');
        
        return lugares.length - 1;
    }

    this.getLugaresPorEspacialidadeAtiva = function(){
        console.log('Geral - getLugaresPorEspacialidadeAtiva');
        for(var i = 0; i < lugares.length; i++){
            var item = lugares[i];
	if(item.ac == true)
                return item;
        }
    }

    this.removeLugar = function(espacialidade, id){
        console.log('Geral - removeLugar');
        for(var i = 0; i < lugares.length; i++){
            var item = lugares[i];
            if(item.e == espacialidade){
                var locais = item.l;
                for(var k = 0 ; k < locais.length; k++){
                    var local = locais[k];
                    if(local.id == id){
                        var obj = locais.splice(k,1);
                        if(listenerLugares)listenerLugares('nocheck',obj);
                    }
                }
	}
        }
    }
        
    this.removerIndicadoresTodos = function(){
        console.log('Geral - removerIndicadoresTodos');
        indicadores = new Array();
    }
    this.removeLugarTodos = function(){
        console.log('Geral - removeLugarTodos');
        lugares = new Array();
    }
      
    this.removeTodosIndicadores = function(){
        console.log('Geral - removeTodosIndicadores');
        indicadores = new Array();
    }
        
    this.getLugares = function(){
        console.log('Geral - getLugares');
        console.log('lugares: '+lugares);
        return lugares;
    }
        
    this.getTotalLugares = function(){
        console.log('Geral - getTotalLugares');
        var total = 0;
        var instance = this;
        $.each(lugares, function(index, value) {
            if(value.e == 7){
                $.each(value.l, function(index , value) {
                    var at = instance.getAreaTematica(value.id);
                    total = parseInt(total) + parseInt(at.getSize());  
                });
            }
            else{
                total += value.l.length;    
            }
        });
        
        return total;
    }

    this.getLugaresString = function(){
        console.log('Geral - getLugaresString');
        ob = new Array();
        c = 0;
        for(var i in lugares){
            temp = new Array();
            for(var j in lugares[i].l){
                temp.push(lugares[i].l[j].id);
            }
            temp_ob = new Object();
            temp_ob.e = lugares[i].e;
            temp_ob.ids = temp.join(',');
            ob.push(temp_ob);
        }
        
        return ob;
    }
        
    this.setLugares = function(value){
        console.log('Geral - setLugares');
        console.log('value: '+value);
        lugares = value;
        if(listenerLugares)
            listenerLugares('reloadList',value);
    }
	
    /**
    * Retorna a posição do elemento adicionado
    * */
    this.addIndicador = function(value){
        console.log('Geral - addIndicador');
        var obj = indicadores.push(value);
        if(listenerIndicadores)
            listenerIndicadores('check',obj);
        return indicadores.length - 1;
    }

    this.getIndicadores = function(){
        console.log('Geral - getIndicadores');
        return indicadores;
    }
        
    this.getIndicadoresString = function(){
        console.log('Geral - getIndicadoresString');
        temp = new Array();
        for(var i in indicadores){
            temp.push(indicadores[i].a+";"+indicadores[i].id);
        }
        return temp.join(",");
    }

    this.setIndicadores = function(value){
        console.log('Geral - setIndicadores');
        //console.log(value);
        indicadores = value;
        if(listenerIndicadores)
            listenerIndicadores('reloadList',value);
    }

    this.removeIndicador = function(index){
        console.log('Geral - removeIndicador');
        for(var i = 0;i<indicadores.length;i++){
            var item = indicadores[i];
	if(i == index){
                var obj = indicadores.splice(i,1);
                if(listenerIndicadores)
                    listenerIndicadores('nocheck',obj);
	}
        }
    }

    this.updateIndicador = function (index, ano){
        console.log('Geral - updateIndicador');
        for(var i = 0; i < indicadores.length; i++){
            var item = indicadores[i];
            if(i == index)
                item.a = ano;
        }
    }

    this.setListenerLugares = function(listener){
        console.log('Geral - setListenerLugares');
//        console.log('++++++++++++++++++');
        listenerLugares = listener;
    }

    this.setListenerIndicadores = function(listener){
        console.log('Geral - setListenerIndicadores');
        listenerIndicadores = listener;
    }

    /**
    * Retorna os indicadores da consulta, desprezando o ano e retirando os indicadores duplicados
    */
    this.getIndicadoresDistintos = function(){
        console.log('Geral - getIndicadoresDistintos');
        var indicadoresDistintos = new Array();
        for(var i = 0; i < indicadores.length; i++){
            var item = indicadores[i];
	if(indicadoresDistintos.indexOf(item.id) == -1)
                indicadoresDistintos.push(item.id);
        }

        return indicadoresDistintos;
    }

    this.removeIndicadoresExtras = function(){
        console.log('Geral - removeIndicadoresExtras');
        var novosIndicadores = indicadores.slice();
        var hasCheck = false;

        for(var i = 0; i < novosIndicadores.length; i++){
            var item = novosIndicadores[i];
	if(item.c == true){
                hasCheck = true;
            }
        }
        if(hasCheck == false){
            for(var i = 0; i < novosIndicadores.length; i++){
                var item = novosIndicadores[i];
                if(i == 0){
                    item.c = true;
                    break;
                }
	}	
        }
        indicadores = novosIndicadores;
    };

    this.removeIndicadoresDuplicados = function(){
        console.log('Geral - removeIndicadoresDuplicados');
        var novosIndicadores = new Array();
        for(var i = 0; i < indicadores.length; i++){
            var item = indicadores[i];
	if(containsInArray(novosIndicadores,item) == false){
                var indicador = new IndicadorPorAno();
                indicador.id = item.id;
                indicador.a = 1;
                indicador.c = false;
                indicador.desc = item.desc;
                indicador.nc = item.nc;
                if(novosIndicadores.length == 0)
                    indicador.c = true;
                novosIndicadores.push(indicador);
	}
        }
        indicadores = novosIndicadores;
    }

    function containsInArray(array,value){
        console.log('Geral - containsInArray');
        for(var i = 0; i < array.length; i++){
            if(array[i].id == value.id)
                return true;
        } 
        return false;
    }
    
    this.getAreaTematica = function (id){
        console.log('Geral - getAreaTematica');
        var area = null;
        for(var i = 0; i < areas_tematicas.length; i++){
            if(areas_tematicas[i].getId() == id){
                area = areas_tematicas[i];
                break;
            }
        }
        return area;
    };
    
    this.AddOrUpdateAreaTematica = function (id,nome,size){
        console.log('Geral - AddOrUpdateAreaTematica');
        var area = null;
//        console.log('id: '+id);
//        console.log('nome: '+nome);
//        console.log('size: '+size);
        for(var i = 0; i < areas_tematicas.length; i++){
            if(areas_tematicas[i].getId() == id){
                area = areas_tematicas[i].setNome(nome).setSize(size);
                break;
            }
        }
        
        if(area == null){
            area = new AreaTematica();
            area.setId(id).setNome(nome).setSize(size);
            areas_tematicas.push(area);
        }
        return area;
    };
}

function IndicadorPorAno(){
    console.log('Geral - IndicadorPorAno');
    this.id; //indicadoor
    console.log('this.id: '+this.id);
    this.a; //ano
    this.c; //checked
    console.log('this.c: '+this.c);
    this.desc; //nome_longo
    this.nc; //nome_curto

    this.setIndicador = function(id,a,c,desc,nc){
        console.log('Geral - setIndicador');
        this.id = id;
        console.log('this.id: '+this.id);
        this.a = a; 
        console.log('this.a: '+this.a);
        this.c = c; 
        console.log('this.c: '+this.c);
        this.desc = desc;
        console.log('this.desc: '+this.desc);
        this.nc  = nc;
        console.log('this.nc: '+this.nc);
    }
}

function Lugar(){
    console.log('Geral - Lugar');
    this.e; //espacialidade;
    this.ac; //ativo
    this.l = new Array(); //array de locais
}

function Local(){
    console.log('Geral - Local');
    this.id;
    this.n; //nome
    this.c; //checado
    this.s; //item selecionado
}

function AreaTematica(){
    console.log('Geral - AreaTematica');
    //atributos
    var _id = 0;
    var _nome = "";
    var _size = 0;   
    
    //metodos
    this.setId = function(id){
        console.log('Geral - setId');
        _id = id;
        return this;
    };
    
    this.getId = function(){
        console.log('Geral - getId');
       return _id;
    };
    
    this.setNome = function(nome){
        console.log('Geral - setNome');
        _nome = nome;
        return this;
    };
    
    this.getNome = function(){
        console.log('Geral - getNome');
        return _nome;
    };
    
    this.setSize = function(size){
        console.log('Geral - setSize');
        _size = size;
        return this;
    };
    
    this.getSize = function(){
        console.log('Geral - getSize');
        return _size;
    };
}
