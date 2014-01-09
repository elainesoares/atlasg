//google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(graficoEvolucaoIDHM);

function graficoFluxoEscolar() {
    var dados = new google.visualization.DataTable(); 
    var array_freq_esc_4a6 = Array();
    var array_freq_esc_12a14 = Array(); 
    var array_freq_esc_16a18 = Array();
    var array_freq_esc_19a21 = Array();


    array_freq_esc_4a6   = jQuery.parseJSON($("#freq_esc_4a6").val());
    array_freq_esc_12a14 = jQuery.parseJSON($("#freq_esc_12a14").val()); 
    array_freq_esc_16a18 = jQuery.parseJSON($("#freq_esc_16a18").val());
    array_freq_esc_19a21 = jQuery.parseJSON($("#freq_esc_19a21").val());


    dados.addColumn('string','Data');
    dados.addRows(4);

    dados.addColumn('number',array_freq_esc_4a6[0].label_ano_referencia);
    dados.addColumn('number',array_freq_esc_4a6[1].label_ano_referencia);
    dados.addColumn('number',array_freq_esc_4a6[2].label_ano_referencia);



    dados.setValue(0,0,array_freq_esc_4a6[0].nomecurto);
    dados.setValue(1,0,array_freq_esc_12a14[0].nomecurto);
    dados.setValue(2,0,array_freq_esc_16a18[0].nomecurto);
    dados.setValue(3,0,array_freq_esc_19a21[0].nomecurto);

    for(i=0;i<array_freq_esc_4a6.length;i++){
        dados.setValue(0,i+1,Number(array_freq_esc_4a6[i].valor));
        dados.setValue(1,i+1,Number(array_freq_esc_12a14[i].valor));
        dados.setValue(2,i+1,Number(array_freq_esc_16a18[i].valor));
        dados.setValue(3,i+1,Number(array_freq_esc_19a21[i].valor));
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
        
        title:"Fluxo Escolar por Faixa Etária"

    });
}