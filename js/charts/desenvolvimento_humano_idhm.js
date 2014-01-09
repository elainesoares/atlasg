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