//google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(drawChart);

function graficoEscolaridadePop91() {
    var dados = new google.visualization.DataTable(); 
    var array_1991 = Array();
    var array_valor_1991 = Array();
    var array_nome_1991 = Array();

    array_1991 = jQuery.parseJSON($("#freq_1991").val());  
 
    dados.addColumn('string','Data');
    dados.addRows(5);
                
    dados.addColumn('number',array_1991[0].nomecurto);
                
    var medio = 0;
    for(i=0;i<array_1991.length;i++)
    {
        
        if('T_MEDIN25M' == array_1991[i].sigla ){
            medio = 100 - array_1991[i].valor;
            break;
        } 
    }
                
    for(i=0;i<array_1991.length;i++)
    {                 
        if('T_FUNDIN25M' == array_1991[i].sigla ){
            array_nome_1991.push("Fundamental incompleto - alfabetizados");
            array_valor_1991.push(100 - array_1991[i].valor);
                
            array_nome_1991.push("Fundamental completo e médio incompleto");
            array_valor_1991.push(array_1991[i].valor - medio);        
        }
        if('T_SUPER25M' == array_1991[i].sigla){
            array_nome_1991.push("médio completo e superior incompleto");
            array_valor_1991.push(medio - array_1991[i].valor);
                        
            array_nome_1991.push("superior completo");
            array_valor_1991.push(array_1991[i].valor);
        }      
    }
                
    for(i=0;i<array_nome_1991.length;i++){
             
        dados.setValue(i,0,array_nome_1991[i]);
        dados.setValue(i,1,Number(array_valor_1991[i]));

    }

    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chartEscolaridadePop91'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
        slices: [ {color: '#CAE1FF'}, {color: '#A4D3EE'}, {color: '#4F94CD'}, {color: '#4682B4'}],
        legend:{position: 'left',alignment: 'center' },
        chartArea:{right: 30,top: 50,width: "300",height: "300"},
        titleTextStyle: {color: 'black', fontSize: 13},
        title:"Escolaridade da população de 25 anos ou mais - 1991"
    });
}
	
function graficoEscolaridadePop00() {
    var dados = new google.visualization.DataTable(); 
    var array_2000 = Array();
    var array_valor_2000 = Array();
    var array_nome_2000 = Array();

    array_2000 = jQuery.parseJSON($("#freq_2000").val());  

    dados.addColumn('string','Data');
    dados.addRows(5);
                
    dados.addColumn('number',array_2000[0].nomecurto);
                
    var medio = 0;
    for(i=0;i<array_2000.length;i++)
    {
        if('T_MEDIN25M' == array_2000[i].sigla ){
            medio = 100 - array_2000[i].valor;
            break;
        } 
    }
                
    for(i=0;i<array_2000.length;i++)
    {                 
        if('T_FUNDIN25M' == array_2000[i].sigla ){
            array_nome_2000.push("Fundamental incompleto - alfabetizados");
            array_valor_2000.push(100 - array_2000[i].valor);
                        
            array_nome_2000.push("Fundamental completo e médio incompleto");
            array_valor_2000.push(array_2000[i].valor - medio);        
        }
        if('T_SUPER25M' == array_2000[i].sigla){
            array_nome_2000.push("médio completo e superior incompleto");
            array_valor_2000.push(medio - array_2000[i].valor);
                        
            array_nome_2000.push("superior completo");
            array_valor_2000.push(array_2000[i].valor);
        }      
    }
                
  
    for(i=0;i<array_nome_2000.length;i++){
                    
        dados.setValue(i,0,array_nome_2000[i]);
        dados.setValue(i,1,Number(array_valor_2000[i]));

    }
                
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chartEscolaridadePop00'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
        slices: [ {color: '#CAE1FF'}, {color: '#A4D3EE'}, {color: '#4F94CD'}, {color: '#4682B4'}],
        legend:'none',
        chartArea:{left: 30,top: 50,width: "185",height: "300"},
        titleTextStyle: {color: 'black', fontSize: 13},
        title:"Escolaridade da população de 25 anos ou mais - 2000"
    });
}

function graficoEscolaridadePop10() {
    var dados = new google.visualization.DataTable(); 
    var array_2010 = Array();
    var array_valor_2010 = Array();
    var array_nome_2010 = Array();

    array_2010 = jQuery.parseJSON($("#freq_2010").val());  

    dados.addColumn('string','Data');
    dados.addRows(5);
                
    dados.addColumn('number',array_2010[0].nomecurto);
                
    var medio = 0;
    for(i=0;i<array_2010.length;i++)
    {
        if('T_MEDIN25M' == array_2010[i].sigla ){
            medio = 100 - array_2010[i].valor;
            break;
        } 
    }
                
    for(i=0;i<array_2010.length;i++)
    {                 
        if('T_FUNDIN25M' == array_2010[i].sigla ){
            array_nome_2010.push("Fundamental incompleto - alfabetizados");
            array_valor_2010.push(100 - array_2010[i].valor);
                        
            array_nome_2010.push("Fundamental completo e médio incompleto");
            array_valor_2010.push(array_2010[i].valor - medio);        
        }
        if('T_SUPER25M' == array_2010[i].sigla){
            array_nome_2010.push("médio completo e superior incompleto");
            array_valor_2010.push(medio - array_2010[i].valor);
                        
            array_nome_2010.push("superior completo");
            array_valor_2010.push(array_2010[i].valor);
        }      
    }
                
  
    for(i=0;i<array_nome_2010.length;i++){
                    
        dados.setValue(i,0,array_nome_2010[i]);
        dados.setValue(i,1,Number(array_valor_2010[i]));

    }
                
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chartEscolaridadePop10'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
        slices: [ {color: '#CAE1FF'}, {color: '#A4D3EE'}, {color: '#4F94CD'}, {color: '#4682B4'}],
        chartArea:{left: 0,top: 50,width: "185",height: "300"},
        legend:'none',
        titleTextStyle: {color: 'black', fontSize: 13},
        title:"Escolaridade da população de 25 anos ou mais - 2010"
    });
}