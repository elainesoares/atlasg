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