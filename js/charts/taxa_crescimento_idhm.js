
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