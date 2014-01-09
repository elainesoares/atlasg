//google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(graficoEvolucaoIDHM);

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
    height: 550,
    title:"Evolução do IDHM",
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

 

