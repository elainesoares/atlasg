function graficoFrequenciaEscolarDe6a14Anos() {
    var dados = new google.visualization.DataTable(); 
    var array = Array();
    var array_freq_esc_4a6_nome = Array();
    var array_freq_esc_4a6_valor = Array();
                
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
            array_freq_esc_4a6_nome.push("Não Frequenta");
            array_freq_esc_4a6_valor.push(100 - array[i].valor);
        }
    }
                
    for(i=0;i<array.length;i++)
    {
        if('T_ATRASO_0_FUND' == array[i].sigla){
            array_freq_esc_4a6_nome.push("Fundamental Sem Atraso"); 
            array_freq_esc_4a6_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_1_FUND' == array [i].sigla){
            array_freq_esc_4a6_nome.push("Fundamental Com Um Ano de Atraso"); 
            array_freq_esc_4a6_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_ATRASO_2_FUND' == array[i].sigla){
            array_freq_esc_4a6_nome.push("Fundamental Com Dois Anos de Atraso"); 
            array_freq_esc_4a6_valor.push((array[i].valor * taxa_frequencia)/100);
        }
        else if('T_FREQMED614' == array[i].sigla){
            array_freq_esc_4a6_nome.push("No Ensino Médio");
            array_freq_esc_4a6_valor.push(array[i].valor);
        }    
        
    }
                
    var valor = 100.00;
    for(i=0;i<array_freq_esc_4a6_nome.length;i++)
    {
        valor = valor - array_freq_esc_4a6_valor[i];
    }
    array_freq_esc_4a6_nome.push("Outros");
    array_freq_esc_4a6_valor.push(valor);
                    
                
    for(i=0;i<array_freq_esc_4a6_nome.length;i++){
        dados.setValue(i,0,array_freq_esc_4a6_nome[i] + " (" +number_format(array_freq_esc_4a6_valor[i], 2, ',', '.')+"%)");
        dados.setValue(i,1,Number(array_freq_esc_4a6_valor[i]));

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
        },slices: [{color: '#F4A460'}, {color: '#CAE1FF'}, {color: '#A4D3EE'}, {color: '#4F94CD'}, {color: '#4682B4'}, {color: '#F5DEB3'}],
        width: 800, 
        height: 500,
        title:"Frequência escolar de 6 a 14 anos - 2010"
    });
                
}