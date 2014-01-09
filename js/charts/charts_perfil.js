google.load("visualization", "1", {
    packages: ["corechart"]
});
google.setOnLoadCallback(graficoDesenvolvimentoHumanoIDHM);

function graficoDesenvolvimentoHumanoIDHM() {
    var dados = new google.visualization.DataTable();

                
    var array_renda         = Array();
    var array_educacao      = Array();
    var array_longevidade   = Array();

    array_renda         = jQuery.parseJSON($("#renda").val());
    array_educacao      = jQuery.parseJSON($("#educacao").val());    
    array_longevidade   = jQuery.parseJSON($("#longevidade").val());
    try{            
        
        dados.addColumn('string','Data');
        dados.addRows(array_renda.length);
        dados.addColumn('number',"Renda");
        dados.addColumn('number',"Longevidade");
        dados.addColumn('number',"Educação");

        for(i=0;i<array_renda.length;i++){
            dados.setValue(i,0,array_renda[i].label_ano_referencia);
            dados.setValue(i,1,Number(array_renda[i].valor));
            dados.setValue(i,2,Number(array_longevidade[i].valor));
            dados.setValue(i,3,Number(array_educacao[i].valor));
        }
        var numberFormatter = new google.visualization.NumberFormat({
            fractionDigits: 3
        });

        numberFormatter.format(dados,1);
        numberFormatter.format(dados,2);
        numberFormatter.format(dados,3);

        var chart = new google.visualization.BarChart(
            document.getElementById('chartDesenvolvimentoHumanoIDHM'));
        chart.draw(dados, 
        {
            series: {
                0: {color: '#3DC4FF'},
                1: {color: '#2587CC'},
                2: {color: '#0F67A6'}
                
                    },
                    hAxis: {
                        gridlines: {
                            count: 0
                        }
                    },
                    bar: {
                        groupWidth: 60
                    },
                    chartArea: {
                        height: 184
                    },
                    'isStacked': true,
                    'legend': 'top'
                });
    }
    catch (e) {
    }
}

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
        if('T_MED25M' == array_1991[i].sigla ){
            medio = array_1991[i].valor;
			break;
        } 
    }
                
    for(i=0;i<array_1991.length;i++)
    {  
		if('T_ANALF25M' == array_1991[i].sigla ){
            array_nome_1991.push("Analfabetos");
            array_valor_1991.push(array_1991[i].valor);
        }
		
        if('T_FUND25M' == array_1991[i].sigla ){
            array_nome_1991.push("Com fundamental completo");
            array_valor_1991.push(array_1991[i].valor - medio);               
        }
        if('T_SUPER25M' == array_1991[i].sigla){		
			array_nome_1991.push("Médio completo");
            array_valor_1991.push(medio - array_1991[i].valor);
			
            array_nome_1991.push("Superior completo");
            array_valor_1991.push(array_1991[i].valor);
        }      
    }
	
	var outros = 100.00;
                
    for(i=0;i<array_valor_1991.length;i++)
    {
        outros = outros - array_valor_1991[i];
    }   
	array_nome_1991.push("Outros");
	if(outros < 0){
		array_valor_1991.push(0);
	}else{
		array_valor_1991.push(outros);
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
        chartArea:{right: 30,top: 50,width: "300",height: "300"}     
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
        if('T_MED25M' == array_2000[i].sigla ){
            medio = array_2000[i].valor;
			break;
        } 
    }
                
    for(i=0;i<array_2000.length;i++)
    {  
		if('T_ANALF25M' == array_2000[i].sigla ){
            array_nome_2000.push("Analfabetos");
            array_valor_2000.push(array_2000[i].valor);
        }
		
        if('T_FUND25M' == array_2000[i].sigla ){
            array_nome_2000.push("Com fundamental completo");
            array_valor_2000.push(array_2000[i].valor - medio);
        }
        if('T_SUPER25M' == array_2000[i].sigla){	                
            array_nome_2000.push("Médio completo");
            array_valor_2000.push(medio - array_2000[i].valor );        
			
            array_nome_2000.push("Superior completo");
            array_valor_2000.push(array_2000[i].valor);
        }      
    }
	
	var outros = 100;
                
    for(i=0;i<array_valor_2000.length;i++)
    {
        outros = outros - array_valor_2000[i];
    }    

	array_nome_2000.push("Outros");
    if(outros < 0){
		array_valor_2000.push(0);
	}else{
		array_valor_2000.push(outros);
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
        chartArea:{left: 30,top: 50,width: "185",height: "300"}
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
        if('T_MED25M' == array_2010[i].sigla ){
            medio = array_2010[i].valor;
			break;
        } 
    }
                
    for(i=0;i<array_2010.length;i++)
    {  
		if('T_ANALF25M' == array_2010[i].sigla ){
            array_nome_2010.push("Analfabetos");
            array_valor_2010.push(array_2010[i].valor);
        }
		
        if('T_FUND25M' == array_2010[i].sigla ){
            array_nome_2010.push("Com fundamental completo");
            array_valor_2010.push(array_2010[i].valor - medio);
                      
        }
        if('T_SUPER25M' == array_2010[i].sigla){
			array_nome_2010.push("Médio completo");
            array_valor_2010.push(medio - array_2010[i].valor); 
			
            array_nome_2010.push("Superior completo");
            array_valor_2010.push(array_2010[i].valor);
        }      
    }
	
	var outros = 100;
                
    for(i=0;i<array_valor_2010.length;i++)
    {
        outros = outros - array_valor_2010[i];
    }    
	
	array_nome_2010.push("Outros");
    if(outros < 0){
		array_valor_2010.push(0);
	}else{
		array_valor_2010.push(outros);
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
        legend:'none'
    });
}
//------------------------------GRAFICO EVOLUCAO--------------------------------------//

function graficoEvolucaoIDHM() {
    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_max_min = Array();
    var array_media_brasil = Array();
    var array_media_estado = Array();

    array = jQuery.parseJSON($("#idhm_mun").val());
    array_max_min = jQuery.parseJSON($("#idhm_max_min_ano").val());    
    array_media_brasil = jQuery.parseJSON($("#idhm_media_brasil").val());
    array_media_estado = jQuery.parseJSON($("#idhm_estado").val());

    var idh = array[0].nome;
    var maior_idh = 'Maior (IDHM)';
    var menor_idh = 'Menor (IDHM)';
    var nac_idh = 'Média do Brasil';
    var uf_idh = 'Média do Estado: '+ array_media_estado[0].nome;

    dados.addColumn('number','Data');
    dados.addRows(array.length);
    dados.addColumn('number',idh);
    dados.addColumn('number',maior_idh);
    dados.addColumn('number',menor_idh);
    dados.addColumn('number',nac_idh);
    dados.addColumn('number',uf_idh);
	
    for(i=0;i<array.length;i++){
        dados.setValue(i,0,Number(array[i].label_ano_referencia));
        dados.setValue(i,1,Number(array[i].valor));
        dados.setValue(i,2,Number(array_max_min[i].maxvalue));
        dados.setValue(i,3,Number(array_max_min[i].minvalue));
        dados.setValue(i,4,Number(array_media_brasil[i].valor));
        dados.setValue(i,5,Number(array_media_estado[i].valor));
    }
	
	var numberFormatter = new google.visualization.NumberFormat({
            fractionDigits: 3
        });

        numberFormatter.format(dados,1);
        numberFormatter.format(dados,2);
        numberFormatter.format(dados,3);
		numberFormatter.format(dados,4);
		numberFormatter.format(dados,5);
		
    var chart = new google.visualization.LineChart(document.getElementById('chartEvolucao'));
    chart.draw(dados, {
        pointSize:4,
        series: {
            0: {
                pointSize: 10, 
                lineWidth: 3, 
                color:'#FF66CC'
            },
            1: {
                color: '#2587CC'
            },
            2: {
                color: 'red'
            },
            3: {
                color: '#2E8B57'
            },
            4: {
                color: '#9AC0CD'
            }
        },
    width: 800, 
    height: 500,
    title:"Evolução do IDHM - "+array[0].nome+" - "+array_media_estado[0].uf,
    hAxis: {
        viewWindow: {
            max: 2010,
            min: 1990
        },
        format:'#',
        gridlines: {
            count: 3
        }
    },
    vAxis: {
        maxValue: 1.0,
        minValue: 0.0,
        gridlines: {
            count: 11
        }
    }
});
}
//---------------------FAIXA ETARIA--------------------------------------//

function graficoFaixaEtaria1991() {
    
    var dados = new google.visualization.DataTable();
    var array_masc = Array();
    var array_femin = Array();
    var array_idade = new Array();
    
    
    array_idade.push('0 a 4','5 a 9','10 a 14','15 a 19','20 a 24','25 a 29',
        '30 a 34','35 a 39','40 a 44','45 a 49','50 a 54','55 a 59','60 a 64',
        '65 a 69','70 a 74','75 a 79','80 e +');
    
    
    array_masc = jQuery.parseJSON($("#piram_masc_1991").val());
    array_femin =jQuery.parseJSON($("#piram_fem_1991").val()); 
    var populacao = jQuery.parseJSON($("#piram_total_1991").val());            
    
    dados.addColumn('string','Data');
    dados.addRows(array_idade.length);
    dados.addColumn('number','Homens');
    dados.addColumn('number','Mulheres');
    
    
    //------------------------------MASC-----------------------------------------//                
    for(i=0;i<array_masc.length;i++){
        
        if(array_masc[i].sigla == 'HOMEM0A4')
        {
            dados.setValue(0,1,Number((array_masc[i].valor/populacao[0].valor).toFixed(2)*100));
        }
        if(array_masc[i].sigla == 'HOMEM5A9')
        {
            dados.setValue(1,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM10A14')
        {
            dados.setValue(2,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM15A19')
        {
            dados.setValue(3,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM20A24')
        {
            dados.setValue(4,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM25A29')
        {
            dados.setValue(5,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM30A34')
        {
            dados.setValue(6,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM35A39')
        {
            dados.setValue(7,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM40A44')
        {
            dados.setValue(8,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM45A49')
        {
            dados.setValue(9,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM50A54')
        {
            dados.setValue(10,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM55A59')
        {
            dados.setValue(11,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM60A64')
        {
            dados.setValue(12,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM65A69')
        {
            dados.setValue(13,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM70A74')
        {
            dados.setValue(14,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM75A79')
        {
            dados.setValue(15,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMENS80')
        {
            dados.setValue(16,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
    
    }
    //---------------FEMININO-----------------------------------------//  
    
    for(i=0;i<array_idade.length;i++){
        dados.setValue(i,0,array_idade[i]);
    }
    for(i=0;i<array_femin.length;i++){
        
        if(array_femin[i].sigla == 'MULH0A4')
        {
            dados.setValue(0,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH5A9')
        {
            dados.setValue(1,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH10A14')
        {
            dados.setValue(2,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH15A19')
        {
            dados.setValue(3,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH20A24')
        {
            dados.setValue(4,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH25A29')
        {
            dados.setValue(5,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH30A34')
        {
            dados.setValue(6,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH35A39')
        {
            dados.setValue(7,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH40A44')
        {
            dados.setValue(8,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH45A49')
        {
            dados.setValue(9,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH50A54')
        {
            dados.setValue(10,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH55A59')
        {
            dados.setValue(11,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH60A64')
        {
            dados.setValue(12,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH65A69')
        {
            dados.setValue(13,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH70A74')
        {
            dados.setValue(14,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH75A79')
        {
            dados.setValue(15,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULHER80')
        {
            dados.setValue(16,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
    
    }
    
    var chart = new google.visualization.BarChart(document.getElementById('chart_piram_1991'));
    
     var options = {
        isStacked: true,
        hAxis: {
            format : ';',
            minValue: -10,
            maxValue: 10
        },
        vAxis: {
            direction: -1
        },
        width: 800,
        height: 400,
        chartArea:{left: 150,top: 30}
    };
    
    var formatter = new google.visualization.NumberFormat({
        negativeParens: true,
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);

    
    chart.draw(dados, options);
}

function graficoFaixaEtaria2000() {
    var dados = new google.visualization.DataTable();
    var array_masc = Array();
    var array_femin = Array();
    var array_idade = new Array();
                
    array_idade.push('0 a 4','5 a 9','10 a 14','15 a 19','20 a 24','25 a 29',
        '30 a 34','35 a 39','40 a 44','45 a 49','50 a 54','55 a 59','60 a 64',
        '65 a 69','70 a 74','75 a 79','80 e +');

                
    array_masc = jQuery.parseJSON($("#piram_masc_2000").val());
    array_femin =jQuery.parseJSON($("#piram_fem_2000").val()); 
    var populacao = jQuery.parseJSON($("#piram_total_2000").val());            

    dados.addColumn('string','Data');
    dados.addRows(array_idade.length);
    dados.addColumn('number','Homens');
    dados.addColumn('number','Mulheres');
                
                
    //------------------------------MASC-----------------------------------------//                
    for(i=0;i<array_masc.length;i++){
                    
        if(array_masc[i].sigla == 'HOMEM0A4')
        {
            dados.setValue(0,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM5A9')
        {
            dados.setValue(1,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM10A14')
        {
            dados.setValue(2,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM15A19')
        {
            dados.setValue(3,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM20A24')
        {
            dados.setValue(4,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM25A29')
        {
            dados.setValue(5,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM30A34')
        {
            dados.setValue(6,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM35A39')
        {
            dados.setValue(7,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM40A44')
        {
            dados.setValue(8,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM45A49')
        {
            dados.setValue(9,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM50A54')
        {
            dados.setValue(10,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM55A59')
        {
            dados.setValue(11,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM60A64')
        {
            dados.setValue(12,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM65A69')
        {
            dados.setValue(13,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM70A74')
        {
            dados.setValue(14,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM75A79')
        {
            dados.setValue(15,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMENS80')
        {
            dados.setValue(16,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }

    }
    //---------------FEMININO-----------------------------------------//  
   
    for(i=0;i<array_idade.length;i++){
        dados.setValue(i,0,array_idade[i]);
    }
    for(i=0;i<array_femin.length;i++){
                    
        if(array_femin[i].sigla == 'MULH0A4')
        {
            dados.setValue(0,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH5A9')
        {
            dados.setValue(1,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH10A14')
        {
            dados.setValue(2,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH15A19')
        {
            dados.setValue(3,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH20A24')
        {
            dados.setValue(4,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH25A29')
        {
            dados.setValue(5,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH30A34')
        {
            dados.setValue(6,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH35A39')
        {
            dados.setValue(7,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH40A44')
        {
            dados.setValue(8,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH45A49')
        {
            dados.setValue(9,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH50A54')
        {
            dados.setValue(10,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH55A59')
        {
            dados.setValue(11,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH60A64')
        {
            dados.setValue(12,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH65A69')
        {
            dados.setValue(13,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH70A74')
        {
            dados.setValue(14,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH75A79')
        {
            dados.setValue(15,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULHER80')
        {
            dados.setValue(16,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        
    }

    var chart = new google.visualization.BarChart(document.getElementById('chart_piram_2000'));
 
     var options = {
        isStacked: true,
        hAxis: {
            format : ';',
            minValue: -10,
            maxValue: 10
        },
        vAxis: {
            direction: -1
        },
        width: 800,
        height: 400,
        chartArea:{left: 150,top: 30}
    };
    
    var formatter = new google.visualization.NumberFormat({
        negativeParens: true,
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    
    chart.draw(dados, options);
}

function graficoFaixaEtaria2010() {
    var dados = new google.visualization.DataTable();
    var array_masc = Array();
    var array_femin = Array();
    var array_idade = new Array();
                
    array_idade.push('0 a 4','5 a 9','10 a 14','15 a 19','20 a 24','25 a 29',
        '30 a 34','35 a 39','40 a 44','45 a 49','50 a 54','55 a 59','60 a 64',
        '65 a 69','70 a 74','75 a 79','80 e +');

                
    array_masc = jQuery.parseJSON($("#piram_masc_2010").val());
    array_femin =jQuery.parseJSON($("#piram_fem_2010").val()); 
    var populacao = jQuery.parseJSON($("#piram_total_2010").val());            

    dados.addColumn('string','Data');
    dados.addRows(array_idade.length);
    dados.addColumn('number','Homens');
    dados.addColumn('number','Mulheres');
                
                
    //------------------------------MASC-----------------------------------------//                
    for(i=0;i<array_masc.length;i++){
                    
        if(array_masc[i].sigla == 'HOMEM0A4')
        {
            dados.setValue(0,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM5A9')
        {
            dados.setValue(1,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM10A14')
        {
            dados.setValue(2,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM15A19')
        {
            dados.setValue(3,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM20A24')
        {
            dados.setValue(4,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM25A29')
        {
            dados.setValue(5,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM30A34')
        {
            dados.setValue(6,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM35A39')
        {
            dados.setValue(7,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM40A44')
        {
            dados.setValue(8,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM45A49')
        {
            dados.setValue(9,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM50A54')
        {
            dados.setValue(10,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM55A59')
        {
            dados.setValue(11,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM60A64')
        {
            dados.setValue(12,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM65A69')
        {
            dados.setValue(13,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM70A74')
        {
            dados.setValue(14,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMEM75A79')
        {
            dados.setValue(15,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }
        if(array_masc[i].sigla == 'HOMENS80')
        {
            dados.setValue(16,1,Number((array_masc[i].valor/populacao[0].valor)*100));
        }

    }
    //---------------FEMININO-----------------------------------------//  
   
    for(i=0;i<array_idade.length;i++){
        dados.setValue(i,0,array_idade[i]);
    }
    for(i=0;i<array_femin.length;i++){
                    
        if(array_femin[i].sigla == 'MULH0A4')
        {
            dados.setValue(0,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH5A9')
        {
            dados.setValue(1,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH10A14')
        {
            dados.setValue(2,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH15A19')
        {
            dados.setValue(3,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH20A24')
        {
            dados.setValue(4,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH25A29')
        {
            dados.setValue(5,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH30A34')
        {
            dados.setValue(6,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH35A39')
        {
            dados.setValue(7,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH40A44')
        {
            dados.setValue(8,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH45A49')
        {
            dados.setValue(9,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH50A54')
        {
            dados.setValue(10,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH55A59')
        {
            dados.setValue(11,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH60A64')
        {
            dados.setValue(12,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH65A69')
        {
            dados.setValue(13,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH70A74')
        {
            dados.setValue(14,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULH75A79')
        {
            dados.setValue(15,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        if(array_femin[i].sigla == 'MULHER80')
        {
            dados.setValue(16,2,Number((array_femin[i].valor/populacao[0].valor)*(-100)));
        }
        
    }

    var chart = new google.visualization.BarChart(document.getElementById('chart_piram_2010'));
 
    var options = {
        isStacked: true,
        
        hAxis: {
            format : ';',
            
            minValue: -10,
            maxValue: 10
        },
        vAxis: {
            direction: -1,
			suffix : '%'
        },
        width: 800,
        height: 400,
        chartArea:{
            left: 150,
            top: 30
        }
    };
    
    var formatter = new google.visualization.NumberFormat({
        negativeParens: true,
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    
    chart.draw(dados, options);
}
//----------------------------------------------GRAFICO FLUXO ESCOLAR------------------------------//
function graficoFluxoEscolar() {
    var dados = new google.visualization.DataTable(); 
    var array_freq_esc_5a6 = Array();
    var array_freq_esc_11a13 = Array(); 
    var array_freq_esc_15a17 = Array();
    var array_freq_esc_18a20 = Array();

    array_freq_esc_5a6   = jQuery.parseJSON($("#freq_esc_5a6").val());
    array_freq_esc_11a13 = jQuery.parseJSON($("#freq_esc_11a13").val()); 
    array_freq_esc_15a17 = jQuery.parseJSON($("#freq_esc_15a17").val());
    array_freq_esc_18a20 = jQuery.parseJSON($("#freq_esc_18a20").val());
	

    dados.addColumn('string','Data');
    dados.addRows(4);
	
    dados.addColumn('number',Number(array_freq_esc_5a6[0].label_ano_referencia));
    dados.addColumn('number',Number(array_freq_esc_5a6[1].label_ano_referencia));
    dados.addColumn('number',Number(array_freq_esc_5a6[2].label_ano_referencia));



    dados.setValue(0,0,array_freq_esc_5a6[0].nomecurto);
    dados.setValue(1,0,array_freq_esc_11a13[0].nomecurto);
    dados.setValue(2,0,array_freq_esc_15a17[0].nomecurto);
    dados.setValue(3,0,array_freq_esc_18a20[0].nomecurto);

    for(i=0;i<array_freq_esc_5a6.length;i++){
        dados.setValue(0,i+1,Number(array_freq_esc_5a6[i].valor));
        dados.setValue(1,i+1,Number(array_freq_esc_11a13[i].valor));
        dados.setValue(2,i+1,Number(array_freq_esc_15a17[i].valor));
        dados.setValue(3,i+1,Number(array_freq_esc_18a20[i].valor));
    }
    
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    formatter.format(dados, 3);

    var chart = new google.visualization.ColumnChart(document.getElementById('chartFluxoEscolar'));
    chart.draw(dados, {
        legend: {
            alignment: 'center'
        },
        series: {
            0: {color: '#FFC0CB'},
            1: {color: '#FF69B4'},
            2: {color: '#FF1493'}
        },
        width: 800, 
        height: 600,
        
        title:"Fluxo Escolar por Faixa Etária - "+array_freq_esc_5a6[0].nome+" - "+array_freq_esc_5a6[0].uf

    });
}
//-------------------------------------FREQUENCIA ESCOLAR ---------------------------------------//

function graficoFrequenciaEscolarDe15a17Anos() {

    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_freq_esc_15a17_nome = Array();
    var array_freq_esc_15a17_valor = Array();
                
    array = jQuery.parseJSON($("#freq_esc_15a17").val());    

    var name_freq = array[0].nome;

    dados.addColumn('string','Data');
    dados.addRows(array.length);
    dados.addColumn('number',name_freq);
                
    var taxa_frequencia;
                
    for(i=0;i<array.length;i++)
    {
        if('T_FLMED' == array[i].sigla){
            taxa_frequencia = array[i].valor;
        } 
        if('T_FREQ15A17' == array[i].sigla ){
            array_freq_esc_15a17_nome.push("Não frequenta");
            array_freq_esc_15a17_valor.push(100 - array[i].valor);
        }
    }
                
    for(i=0;i<array.length;i++)
    {
        
        if('T_ATRASO_0_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No ensino médio sem atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
		else if('T_FREQFUND1517' == array[i].sigla){
            array_freq_esc_15a17_nome.push("Frequentando o fundamental");
            array_freq_esc_15a17_valor.push(array[i].valor);
        }
        else if('T_ATRASO_1_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No ensino médio com um ano de atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_2_MED' == array[i].sigla){
            array_freq_esc_15a17_nome.push("No ensino médio com dois anos de atraso"); 
            array_freq_esc_15a17_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_FREQSUPER1517' == array[i].sigla){
            array_freq_esc_15a17_nome.push("Frequentando o curso superior");
            array_freq_esc_15a17_valor.push(array[i].valor);
        }
    }
    var valor = 100;
                
    for(i=0;i<array_freq_esc_15a17_nome.length;i++)
    {
        valor = valor - array_freq_esc_15a17_valor[i];
    }        
		
    array_freq_esc_15a17_nome.push("Outros");
    if(valor < 0){
		array_freq_esc_15a17_valor.push(Number(0));
    }else{
		array_freq_esc_15a17_valor.push(Number(valor));
	}
    for(i=0;i<array_freq_esc_15a17_nome.length;i++){
        dados.setValue(i,0,array_freq_esc_15a17_nome[i] + " (" +number_format(array_freq_esc_15a17_valor[i], 2, ',', '.')+"%)");
        dados.setValue(i,1,Number(array_freq_esc_15a17_valor[i]));

    }
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chart_freq_15a17'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
		slices: [{color: '#DFDFDF'}, {color: '#FF9900'}, {color: '#FFAD5A'}, {color: '#FFC492'}, {color: '#FF6699'}, {color: '#0099CC'},{color: '#00CC66'}],
        pieSliceText: 'value',
        width: 800, 
        height: 400,
        title:"Frequência escolar de 15 a 17 anos - "+array[0].nome+" - "+array[0].uf+" - 2010"
    });
                
}

function graficoFrequenciaEscolarDe18a24Anos() {

    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_freq_esc_18a24_nome = Array();
    var array_freq_esc_18a24_valor = Array();
                
    array = jQuery.parseJSON($("#freq_esc_18a24").val());    
    
    var name_freq = array[0].nome;
    
    dados.addColumn('string','Data');
    dados.addRows(array.length + 1);//#Foi preciso adicionar um aqui
    dados.addColumn('number',name_freq);
                
    //var taxa_frequencia;
                
    for(i=0;i<array.length;i++)
    {
		if('T_FREQ18A24' == array[i].sigla ){
		array_freq_esc_18a24_nome.push("Não frequenta");
		array_freq_esc_18a24_valor.push(100 - array[i].valor);
        }
    }
                
    for(i=0;i<array.length;i++)
    {        
        if('T_FREQFUND1824' == array[i].sigla){
            array_freq_esc_18a24_nome.push("Frequentando o fundamental");
            array_freq_esc_18a24_valor.push(array[i].valor);
        }
        else if('T_FREQMED1824' == array[i].sigla){
            array_freq_esc_18a24_nome.push("Frequentando o ensino médio");
            array_freq_esc_18a24_valor.push(array[i].valor);
        }
        else if('T_FLSUPER' == array[i].sigla){
            array_freq_esc_18a24_nome.push("Frequentando o curso superior");
            array_freq_esc_18a24_valor.push(array[i].valor);
        }
    }
    var valor = 100;
                
    for(i=0;i<array_freq_esc_18a24_nome.length;i++)
    {
        valor = valor - array_freq_esc_18a24_valor[i];
    }         
    array_freq_esc_18a24_nome.push("Outros");
	
	if(valor < 0){
		array_freq_esc_18a24_valor.push(Number(0));
    }else{
		array_freq_esc_18a24_valor.push(Number(valor));
	}
          
    for(i=0;i<array_freq_esc_18a24_nome.length;i++){
        dados.setValue(i,0,array_freq_esc_18a24_nome[i] + " (" +number_format(array_freq_esc_18a24_valor[i], 2, ',', '.')+"%)");
        dados.setValue(i,1,Number(array_freq_esc_18a24_valor[i]));

    }
    
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chart_freq_18a24'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
		slices: [{color: '#DFDFDF'}, {color: '#00A7DE'}, {color: '#FF6699'}, {color: '#FFDBB8'}, {color: '#00CC66'}],
        pieSliceText: 'value',
        width: 800, 
        height: 400,
        title:"Frequência escolar de 18 a 24 anos - "+array[0].nome+" - "+array[0].uf+" - 2010"
    });
                
}

function graficoFrequenciaEscolarDe6a14Anos() {
    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_freq_esc_6a14_nome = Array();
    var array_freq_esc_6a14_valor = Array();
                
    array = jQuery.parseJSON($("#freq_esc_6a14").val());    

    var name_freq = array[0].nome;


    dados.addColumn('string','Data');
    dados.addRows(array.length);
    dados.addColumn('number',name_freq);
                
    var taxa_frequencia;
                
    for(i=0;i<array.length;i++)
    {
        if('T_FLFUND' == array[i].sigla){
            taxa_frequencia = array[i].valor;
        } 
        else if('T_FREQ6A14' == array[i].sigla ){
            array_freq_esc_6a14_nome.push("Não frequenta");
            array_freq_esc_6a14_valor.push(100 - array[i].valor);
        }
    }
                
    for(i=0;i<array.length;i++)
    {
        if('T_ATRASO_0_FUND' == array[i].sigla){
            array_freq_esc_6a14_nome.push("Fundamental sem atraso"); 
            array_freq_esc_6a14_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_1_FUND' == array [i].sigla){
            array_freq_esc_6a14_nome.push("Fundamental com um ano de atraso"); 
            array_freq_esc_6a14_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_2_FUND' == array[i].sigla){
            array_freq_esc_6a14_nome.push("Fundamental com dois anos de atraso"); 
            array_freq_esc_6a14_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_FREQMED614' == array[i].sigla){
            array_freq_esc_6a14_nome.push("No ensino médio");
            array_freq_esc_6a14_valor.push(array[i].valor);
        }    
        
    }
                
    var valor = 100.00;
    for(i=0;i<array_freq_esc_6a14_nome.length;i++)
    {
        valor = valor - array_freq_esc_6a14_valor[i];
    }
    array_freq_esc_6a14_nome.push("Outros");
	
    if(valor < 0){
		array_freq_esc_6a14_valor.push(Number(0));
    }else{
		array_freq_esc_6a14_valor.push(Number(valor));
	}
                    
                
    for(i=0;i<array_freq_esc_6a14_nome.length;i++){
        dados.setValue(i,0,array_freq_esc_6a14_nome[i] + " (" +number_format(array_freq_esc_6a14_valor[i], 2, ',', '.')+"%)");
        dados.setValue(i,1,Number(array_freq_esc_6a14_valor[i]));

    }
                
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
          
    var chart = new google.visualization.PieChart(document.getElementById('chart_freq_6a14'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
		slices: [{color: '#DFDFDF'}, {color: '#FF8891'}, {color: '#FFA89D'}, {color: '#FFBBAD'}, {color: '#FFE7D1'}, {color: '#00CC66'}],
        width: 800, 
        height: 400,
        title:"Frequência escolar de 6 a 14 anos - "+array[0].nome+" - "+array[0].uf+" - 2010"
    });
                
}

 //-----------------------GRAFICO FREQUENCIA ESCOLAR------------------------------------//
 
function graficoFrequenciaEscolar() {
    var dados = new google.visualization.DataTable(); 
    var array_freq_esc_mun = Array();
    var array_freq_esc_uf = Array(); 
    var array_freq_esc_pais = Array();

    array_freq_esc_mun   = jQuery.parseJSON($("#freq_esc_mun").val());
    array_freq_esc_uf = jQuery.parseJSON($("#freq_esc_uf").val()); 
    array_freq_esc_pais = jQuery.parseJSON($("#freq_esc_pais").val());
	
    var array_freq_esc_5a6 = Array();
    var array_freq_esc_11a13 = Array(); 
    var array_freq_esc_15a17 = Array();
    var array_freq_esc_18a20 = Array();
		
    for(i=0;i<array_freq_esc_mun.length;i++)
    {
        if('T_FREQ5A6' == array_freq_esc_mun[i].sigla ){ 	
            array_freq_esc_5a6.push(array_freq_esc_mun[i]);      
        }
        if('T_FUND11A13' == array_freq_esc_mun[i].sigla){
            array_freq_esc_11a13.push(array_freq_esc_mun[i]);
        }
        if('T_FUND15A17' == array_freq_esc_mun[i].sigla){
            array_freq_esc_15a17.push(array_freq_esc_mun[i]);
        }
        if('T_MED18A20' == array_freq_esc_mun[i].sigla){
            array_freq_esc_18a20.push(array_freq_esc_mun[i]);
        }
    }
    for(i=0;i<array_freq_esc_uf.length;i++)
    {
		if('T_FREQ5A6' == array_freq_esc_uf[i].sigla ){ 	
            array_freq_esc_5a6.push(array_freq_esc_uf[i]);       
        }
        if('T_FUND11A13' == array_freq_esc_uf[i].sigla){
            array_freq_esc_11a13.push(array_freq_esc_uf[i]);
        }
        if('T_FUND15A17' == array_freq_esc_uf[i].sigla){
            array_freq_esc_15a17.push(array_freq_esc_uf[i]);
        }
        if('T_MED18A20' == array_freq_esc_uf[i].sigla){
            array_freq_esc_18a20.push(array_freq_esc_uf[i]);
        }	
	}
	for(i=0;i<array_freq_esc_pais.length;i++)
    {
        if('T_FREQ5A6' == array_freq_esc_pais[i].sigla ){ 	
            array_freq_esc_5a6.push(array_freq_esc_pais[i]);       
        }
        if('T_FUND11A13' == array_freq_esc_pais[i].sigla){
            array_freq_esc_11a13.push(array_freq_esc_pais[i]);
        }
        if('T_FUND15A17' == array_freq_esc_pais[i].sigla){
            array_freq_esc_15a17.push(array_freq_esc_pais[i]);
        }
        if('T_MED18A20' == array_freq_esc_pais[i].sigla){
            array_freq_esc_18a20.push(array_freq_esc_pais[i]);
        }
    }
	
    dados.addColumn('string','Data');
    dados.addRows(4);            
		
    dados.addColumn('number',array_freq_esc_mun[0].nome);
    dados.addColumn('number','Estado: ' + array_freq_esc_uf[0].uf);
    dados.addColumn('number','Brasil');
		
    dados.setValue(0,0,array_freq_esc_5a6[0].nomecurto);
    dados.setValue(1,0,array_freq_esc_11a13[0].nomecurto);
    dados.setValue(2,0,array_freq_esc_15a17[0].nomecurto);
    dados.setValue(3,0,array_freq_esc_18a20[0].nomecurto);
		
    for(i=0;i<array_freq_esc_5a6.length;i++){
	     
        dados.setValue(0,i+1,Number(array_freq_esc_5a6[i].valor));
        dados.setValue(1,i+1,Number(array_freq_esc_11a13[i].valor));
        dados.setValue(2,i+1,Number(array_freq_esc_15a17[i].valor));
        dados.setValue(3,i+1,Number(array_freq_esc_18a20[i].valor));
    }
	
     var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    
    formatter.format(dados, 1);
    formatter.format(dados, 2);
    formatter.format(dados, 3);
    
    var chart = new google.visualization.ColumnChart(document.getElementById('chartFrequenciaEscolar'));
    chart.draw(dados, {
        legend: {
            alignment: 'center'
        },
        series: {
            0: {
                color:'#FF66CC'
            },
            1: {
                color: '#9AC0CD'
            },
            2: {
                color: '#2E8B57'
            }
        },
        width: 800, 
        height: 600,
        title:"Fluxo Escolar por Faixa Etária - "+array_freq_esc_mun[0].nome+" - "+array_freq_esc_uf[0].uf+" - 2010" 
	
    });
}

//-----------------------GRAFICO TAXA DE CRESCIMENTO-----------------------------------//

function graficoTaxaCrescimentoIDHM() {
    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_media_brasil = Array();
    var array_media_estado = Array();

    array = jQuery.parseJSON($("#idhm_mun").val());  
    array_media_brasil = jQuery.parseJSON($("#idhm_media_brasil").val());
    array_media_estado = jQuery.parseJSON($("#idhm_estado").val());

    var idh = array[0].nome;
    var nac_idh = 'Média do Brasil';
    var uf_idh = 'Média do Estado: '+ array_media_estado[0].nome;

    dados.addColumn('string','Data');
    dados.addRows(2);
    dados.addColumn('number',idh);
    dados.addColumn('number',nac_idh);
    dados.addColumn('number',uf_idh);
                
    dados.setValue(0,0,"1991-2000");
    dados.setValue(1,0,"2000-2010");
                
                
    //((IDHM 2010/ IDHM 2000)-1)*100
    var valor1991= 0;
    var valor2000= 0;
    var valor2010= 0;
    var valor_mun = new Array();
    var valor_uf = new Array();
    var valor_pais = new Array();
                
    for(i=0;i<=array.length-1;i++){
        if(array[i].label_ano_referencia == 1991){
            valor1991 = array[i].valor;
        }
        else if(array[i].label_ano_referencia == 2000){
            valor2000 = array[i].valor;
        }
        else if(array[i].label_ano_referencia == 2010){ 
            valor2010 = array[i].valor;         
            valor_mun.push(((valor2000/valor1991)-1)*100);
            valor_mun.push(((valor2010/valor2000)-1)*100); 
        }
    }
    for(i=0;i<=array_media_brasil.length-1;i++){
        if(array_media_brasil[i].label_ano_referencia == 1991){
            valor1991 = array_media_brasil[i].valor;
        }
        else if(array_media_brasil[i].label_ano_referencia == 2000){
            valor2000 = array_media_brasil[i].valor;
        }
        else if(array_media_brasil[i].label_ano_referencia == 2010){ 
            valor2010 = array_media_brasil[i].valor;         
            valor_uf.push(((valor2000/valor1991)-1)*101);
            valor_uf.push(((valor2010/valor2000)-1)*101); 
        }
    }
                
    for(i=0;i<=array_media_estado.length-1;i++){
        if(array_media_estado[i].label_ano_referencia == 1991){
            valor1991 = array_media_estado[i].valor;
        }
        else if(array_media_estado[i].label_ano_referencia == 2000){
            valor2000 = array_media_estado[i].valor;
        }
        else if(array_media_estado[i].label_ano_referencia == 2010){ 
            valor2010 = array_media_estado[i].valor;         
            valor_pais.push(((valor2000/valor1991)-1)*102);
            valor_pais.push(((valor2010/valor2000)-1)*102); 
        }
    }
 
                
                 
    for(i=0;i<valor_mun.length;i++){ 
        dados.setValue(i,1,Number(valor_mun[i]));
        dados.setValue(i,2,Number(valor_uf[i]));
        dados.setValue(i,3,Number(valor_pais[i]));
    }
    var formatter = new google.visualization.NumberFormat({
        suffix: '%'
    });
    formatter.format(dados,1);
    formatter.format(dados,2);
    formatter.format(dados,3);
                
    var chart = new google.visualization.LineChart(document.getElementById('taxaCrescimentoIDHM'));
    chart.draw(dados, {
        pointSize:4,
        series: {
            0: {
                pointSize: 15, 
                lineWidth: 5, 
                color:'#FF66CC'
            },
            1: {
                color: '#00CC00',
                pointSize: 10
            },
            2: {
                color: 'red'
            },
            3: {
                color: '#C0C0C0',
                pointSize: 12
            }
        },
    width: 800, 
    height: 600,
    title:"Taxa de Crescimento - IDHM",
    vAxis: {
        maxValue: 1.0,
        minValue: 0.0,
        gridlines: {
            count: 11
        }
    }
    });
}

//-------------------TRABALHOS--------------------------------------------//

function graficoTrabalho() {
                
                
    var dados = new google.visualization.DataTable(); 
    var array_trab_2010 = Array();
    var array_valor_trab_2010 = Array();
    var array_nome_trab_2010 = Array();

    array_trab_2010 = jQuery.parseJSON($("#trabalho_perfil").val());  

    dados.addColumn('string','Data');
    dados.addRows(5);
                
    dados.addColumn('number',array_trab_2010[0].nomecurto);
                
    var peso18 = 0;
    var t_ativ18m = 0;
                
    for(i=0;i<array_trab_2010.length;i++)
    {   
        if('PESO18' == array_trab_2010[i].sigla ){
            peso18 = array_trab_2010[i].valor;
        }               
        if('T_ATIV18M'== array_trab_2010[i].sigla){
            t_ativ18m = array_trab_2010[i].valor;
        }
    }
    array_valor_trab_2010.push((t_ativ18m/100)*peso18);
    array_nome_trab_2010.push("População economicamente ativa");

    array_valor_trab_2010.push(peso18 - (((t_ativ18m/100)*peso18)));
    array_nome_trab_2010.push("População economicamente não ativa");

  
    for(i=0;i<array_valor_trab_2010.length;i++){
                    
        dados.setValue(i,0,array_nome_trab_2010[i]);
        dados.setValue(i,1,Number(array_valor_trab_2010[i]));

    }
    var numberFormatter = new google.visualization.NumberFormat({
        fractionDigits: 0
    });
    numberFormatter.format(dados,1);
    var chart = new google.visualization.PieChart(document.getElementById('chart_trabalho'));
    chart.draw(dados, {
        hAxis: {
            minValue: 0,
            maxValue: 100
        },
        width: 600, 
        height: 500,
        chartArea:{left: 20, top: 60},
        legend: {position: 'none'}
    });
}
//---------------------------TRABALHOS ATIVOS----------------------------------//

function graficoTrabalhoAtivos() {
                
    var dados = new google.visualization.DataTable(); 
    var array_trab_2010 = Array();
    var array_valor_trab_2010 = Array();
    var array_nome_trab_2010 = Array();

    array_trab_2010 = jQuery.parseJSON($("#trabalho_ativo").val());  

    dados.addColumn('string','Data');
    dados.addRows(2);
                
    dados.addColumn('number','Ocupados');
    dados.addColumn('number','Desocupados');
                
    var peso18 = 0;
    var t_des18m = 0;
    var t_ativ18m = 0;
                
    for(i=0;i<array_trab_2010.length;i++)
    {   
                       
        if('PESO18' == array_trab_2010[i].sigla ){
            peso18 = array_trab_2010[i].valor;
        }
        if('T_DES18M' == array_trab_2010[i].sigla){
            t_des18m = array_trab_2010[i].valor;
        }                
        if('T_ATIV18M'== array_trab_2010[i].sigla){
            t_ativ18m = array_trab_2010[i].valor;
        }
    }
    dados.setValue(0,0,array_trab_2010[0].label_ano_referencia);
    dados.setValue(0,1,(((t_ativ18m/100)*peso18)-((t_des18m/100)*peso18)));
    dados.setValue(0,2,(peso18*(t_des18m/100)));
                
    var numberFormatter = new google.visualization.NumberFormat({
        fractionDigits: 0
    });
    numberFormatter.format(dados,1);
    numberFormatter.format(dados,2);
	
    var chart = new google.visualization.ColumnChart(
        document.getElementById('chart_trabalho_ativos'));
    chart.draw(dados, {
        'isStacked': true, 
        'legend': 'right',
         series: {
                0: {color: '#4682B4'},
                1: {color: '#87CEEB'}
                    },
        vAxis: {
            gridlines: {
                count:0
            }
        },
        chartArea:{left: 0},
        width: 350, 
        height: 400
    });
}