function graficoTrabalho() {
                
                
    var dados = new google.visualization.DataTable(); 
    var array_trab_2010 = Array();
    var array_valor_trab_2010 = Array();
    var array_nome_trab_2010 = Array();

    array_trab_2010 = jQuery.parseJSON($("#trabalho").val());  

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
    array_nome_trab_2010.push("População Ativa");

    array_valor_trab_2010.push(peso18 - (((t_ativ18m/100)*peso18)));
    array_nome_trab_2010.push("População Não Ativa");

  
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
        'legend': 'top'
    });
}


